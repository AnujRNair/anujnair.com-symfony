# Obtaining your Transaction List from Affiliate Window by connecting to their Publisher Service

Affiliate Window have a way of connecting to their web services so that you can download all transactions you have generated through them.

This quick post intends to put you on the correct course so that you can do so in Microsoft's Visual Studio / VB / VB.net - This will allow you to quickly import your transactions into Microsoft's SQL Server for any data manipulation that you like. Something like this can be very handy, especially if you load in and standardize transactions from other affiliates as well - You'll then be able to query all of your affiliate data at once

Affiliate Window services are queried by sending SOAP requests to their API. With each request you make, you will need to pass an API key along so that the AWin service can authenticate you. If you need to generate your API key, you can do so under the Settings Tab -> Manage API Credentials

There are a few things we will need to get around whilst making the query calls:

* The AWin service only returns 1000 rows at a time
* The AWin service tells you how many rows are available in the response of your first API call
* We will need to make a few different calls to check when a transaction was made, and was validated (If the status matters to you) - transactions could be validated a few weeks later!

To combat this, we will keep querying the service in blocks of 1000 rows until there are no more rows remaining. We can do so with something like the following:

```clike
' You might not need to import all of these ...
Imports System
Imports System.IO
Imports System.Text
Imports System.Data
Imports System.Math
Imports System.Net
Imports System.Xml
Imports System.Collections
Imports Microsoft.SqlServer.Dts.Runtime

Dim urlPath as String = "http://api.affiliatewindow.com/v3/AffiliateService"
Dim publisherId as String = 'xxxxx'
Dim publisherApiKey as String = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'
Dim datetimeStart as String = '2013-01-01T00:00:00'
Dim datetimeEnd as String = '2013-01-07T23:59:59'

' We can store all of our results into a Hashtable, sDateType internal var to know how to query the service
Dim hashtable As New Hashtable
Dim sDateType as String

For i As Int32 = 0 To 1

    ' Lets set / reset some default variables for tracking
    Dim rowsObtained as Int32 = 0
    Cim totalRequests as Int32 = 0

    ' Want to check for both validations and transactions, so lets switch between the two
    If i = 0 Then
        sDateType = "validation"
    Else
        sDateType = "transaction"
    End If

    ' Go infinitely until we're asked to break below
    While 1 = 1
        totalRequests += 1

        ' Build SOAP Message
        Dim soapMessage As String = "<?xml version='1.0' encoding='UTF-8'?>" & _
           "<SOAP-ENV:Envelope xmlns:SOAP-ENV='http://schemas.xmlsoap.org/soap/envelope/'  xmlns:xsd='http://www.w3.org/2001/XMLSchema'  xmlns:ns1='http://api.affiliatewindow.com/'>" & _
           "<SOAP-ENV:Header>" & _
           "<ns1:UserAuthentication SOAP-ENV:mustUnderstand='1' SOAP-ENV:actor='http://api.affiliatewindow.com'>" & _
           "   <ns1:iId>" & publisherId & "</ns1:iId>" & _
           "   <ns1:sPassword>" & publisherApiKey & "</ns1:sPassword>" & _
           "<ns1:sType>affiliate</ns1:sType>" & _
           "</ns1:UserAuthentication>" & _
           "<ns1:getQuota SOAP-ENV:mustUnderstand='1' SOAP-ENV:actor='http://api.affiliatewindow.com'>true</ns1:getQuota>" & _
           "</SOAP-ENV:Header>" & _
           "      <SOAP-ENV:Body>" & _
           "         <ns1:getTransactionList>" & _
           "              <ns1:dStartDate>" & datetimeStart & "</ns1:dStartDate>" & _
           "              <ns1:dEndDate>" & datetimeEnd & "</ns1:dEndDate>" & _
           "              <ns1:sDateType>" & sDateType & "</ns1:sDateType>"
        If totalRequests > 1 Then
            soapMessage += "         <ns1:iOffset>" & ((totalRequests - 1) * 1000) & "</ns1:iOffset>"
        End If

        soapMessage += "         <ns1:iLimit>1000</ns1:iLimit>"
        soapMessage += "         </ns1:getTransactionList>" & _
           "      </SOAP-ENV:Body>" & _
           "</SOAP-ENV:Envelope>"

        ' Post the SOAP Message and load into an XML var
        Dim encoding As New ASCIIEncoding()
        Dim byte1 As Byte() = encoding.GetBytes(soapMessage)
        Dim objHTTPReq As HttpWebRequest = CType(System.Net.WebRequest.CreateDefault(New System.Uri(urlPath)), HttpWebRequest)
        objHTTPReq.ContentType = "text/xml"
        objHTTPReq.ContentLength = byte1.Length
        objHTTPReq.Method = "POST"
        Dim newStream As Stream = objHTTPReq.GetRequestStream()
        newStream.Write(byte1, 0, byte1.Length)
        Dim objHTTPRes As HttpWebResponse = CType(objHTTPReq.GetResponse(), HttpWebResponse)
        Dim objXML As XmlDocument = New XmlDocument()
        objXML.Load(objHTTPRes.GetResponseStream())
        newStream.Close()

        ' Let see how many rows are available and how many were returned
        Dim objRoot As XmlElement = objXML.DocumentElement()
        Dim sRowRet As Int32 = CType(objRoot.GetElementsByTagName("ns1:getTransactionListCountReturn").Item(0).ChildNodes.Item(0).InnerText, Int32)
        Dim sRowAva As Int32 = CType(objRoot.GetElementsByTagName("ns1:getTransactionListCountReturn").Item(0).ChildNodes.Item(1).InnerText, Int32)

        ' Add the rows returned to the var which is tracking so we can compare lower down
        rowsObtained += sRowRet

        ' You'll want to loop through all of your rows and add them to your hash table here

        ' If we're done, no more querying
        If rowsObtained >= sRowAva Or totalRequests > 20 Then
            Exit While
        End If

    End While
Next i
```

You can then standardize and insert into your DB as necessary.

Let me know your thoughts!
