# Tracking External Storage Disk Space through Telegraf, InfluxDB, and Docker on MacOS

I have a couple of external hard drives plugged into my Mac, and wanted to track their available space through my telegraf instance, so that I could graph the results in Grafana.

It seems that the `input.disk` plugin only tracks mounted drives, of which my external hard drives were not.

#### My setup

I'm currently running telegraf through a docker container on a Mac Mini. I have 2 external hard drives plugged into the Mac Mini via USB.

#### Solution

I used the [Telegraf Exec Input Plugin](https://github.com/influxdata/telegraf/blob/master/plugins/inputs/exec/README.md) (which is already built into telegraf) to accomplish this.

**First**, I created a simple bash script to output the size of folders that were passed in as args, and saved it at the location: `/path/to/telegraf/scripts/disk-usage.sh`

```bash
#!/bin/bash

for p in "$@"
do
  df "$p" | awk -v path="$p" '{ if (NR!=1) { print path","$3","$4 }; }'
done
```

Then I made it executable:
```bash
$ chmod +x /path/to/telegraf/scripts/disk-usage.sh
```

**Explanation**:
- Usage: `$ bash /path/to/telegraf/scripts/disk-usage.sh /Volumes/ExternalHD1 /VolumesExternalHD2`
- The script loops through the paths passed as args (in this case `/Volumes/ExternalHD1` and `/Volumes/ExternalHD2`) and runs `df` against them. This outputs tables like below:

```text
Filesystem            512-blocks       Used Available Capacity   iused      ifree %iused  Mounted on
/Volumes/ExternalHD1  3906619488 3339296832 567322656    86% 417412102   70915332   85%   /Volumes/ExternalHD1

# and 

Filesystem            512-blocks       Used Available Capacity   iused      ifree %iused  Mounted on
/Volumes/ExternalHD2  3906619488 3401009824 505609664    88% 425126226   63201208   87%   /Volumes/ExternalHD2
```

- For each table, we then reformat the result into a format we want using `awk`. We ignore the first row, and print the results of the second in a specific format. Each `$` variable represents a column from the table (`awk` separates the variables automatically by the spaces).
- The result is a single table that looks like the following:

```text
/Volumes/ExternalHD1,1669355608,283661328
/Volumes/ExternalHD2,1700212104,252804832
```

Telegraf can read this using its CSV parser, and then pass the result to InfluxDB, for us to then graph with Grafana!

**Next**, I mounted all the necessary directories in my docker config so that telegraf could see my custom script, and my external hard drives. Relevant config:

```yaml
services:
  telegraf:
    container_name: telegraf
    image: telegraf
    volumes:
      - /path/to/telegraf:/etc/telegraf
      - /Volumes/ExternalHD1:/external/ExternalHD1:ro
      - /Volumes/ExternalHD2:/external/ExternalHD2:ro
```

- `/path/to/telegraf` was already mapped and holds my persistent config. My custom script is located at `/path/to/telegraf/scripts/disk-usage.sh`, so will be accessible inside the container as `/etc/telegraf/scripts/disk-usage.sh`.
- My 2 external hard drives were mapped to a new top level folder I called `external`. You could map them anywhere, really. I also ensured that telegraf could only read data from them via the `:ro` flag at the end of the mapping.

**Finally**, I added the configuration into `telegraf.conf` to enable the `exec` plugin to run this custom script:

```text
[[inputs.exec]]
  commands = ["/etc/telegraf/scripts/disk-usage.sh /external/ExternalHD1 /external/ExternalHD2"]
  timeout = "5s"
  name_override = "df"
  name_suffix = ""
  
  data_format = "csv"
  csv_header_row_count = 0
  csv_column_names = ['dir', 'used', 'available']
  csv_column_types = ['string', 'int', 'int']
  csv_tag_columns = ['dir']
```

**Explanation:**
- `commands` holds the command we want to run. Notice that the paths to the external drives are the container's internal paths (i.e. using `/external` vs `/Volumes`)
- `name_override` holds the measurement name that telegraf will write to influxdb. You can customize this to be something more descriptive if you like.
- `data_format` is the format for the output of our script. We made `awk` output it in a `csv` format.
- `csv_column_names` are the column names for the script's output.
- `csv_tag_columns` is the name of the column that we want to tag the other metrics by. Kind of like a `group by` in SQL.

We can test if this is working correctly by connecting to the telegraf container, and running a test command:
```bash
$ docker exec -it telegraf /bin/bash
$ telegraf --debug --config /etc/telegraf/telegraf.conf --input-filter exec --test
```

This should output something like the following:
```text
> df,dir=/external/ExternalHD1,host=d32555eec5da available=283661392i,used=1669355544i 1630894287000000000
> df,dir=/external/ExternalHD2,host=d32555eec5da available=252804832i,used=1700212104i 1630894287000000000
```

If you get a permissions denied error, make sure you've made the script executable (instructions above)

#### Grafana

We can graph these results in grafana to see how our disk usage now changes over time.
- Create a new time series panel with the following:
  - From `default` `df`
  - Select `field(available)` `mean()`
  - Group By `time($__interval)` `tag(dir)` `fill(none)`
  - Format As `Time Series` Alias `$tag_dir`
- Then go into the Options panel, scroll down to Standard Options -> Unit, and select `kilobytes`

Your graph should now update over time to show you how much free space you have on each external hard drive.

You can also make similar graphs for used space and total space by changing the `field` value.
