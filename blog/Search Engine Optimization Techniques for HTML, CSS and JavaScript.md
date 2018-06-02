# Search Engine Optimization Techniques for HTML, CSS and JavaScript

Search Engine Optimization, or SEO for short, is the process of naturally increasing the page rank of a website on search engines.

For businesses, this is a huge thing; If someone searches for a product or a service, a business wants their company to appear first on search engines such as Google, as people will most likely then buy from them.

This post will cover ways a web developer can use certain HTML, CSS and JavaScript techniques whilst creating a website to hopefully increase his page rank on search engines. This is a Search Engine Optimization post solely aimed at HTML, CSS and JavaScript coding, and not on other SEO methods such as Back Linking, PHP Methods, or Server Methods - I may expand on those in another post.

That being said, search engines keep their ranking algorithms secret and so no one actually knows the real effect of all of these techniques. These are simply tried and tested methods which developers believe contribute to a high page rank. These is also no silver bullet for Search Engine Optimization - it is a constant process and if you want to succeed, you have to constantly adapt your methods to what is working and what isn't.

Different things have different weightings in search engines, so I'll try and make sure the most effective methods are listed at the top of this article, and go down in descending order.

#### Have Good Content
The easiest way to have a good page rank is to make sure that you have good content on your web pages. If you have good content which is keyword rich, search engines will index you on hey keywords you are targeting, people will link to your site increasing your back links and your page rank will increase.

#### Your Website Title
The title you give your website is important. Include the keywords you are targeting into your title, but keep it short, descriptive and keyword rich. Short because it is estimated that Google only pays attention to the first 70 characters of your title tag, descriptive because you want people to look at the title and know that they will be entering a website which is going to help them find what they are looking for, and keyword rich because the title tag has a decent weighting on your page ranking, and you want to appear high up in search results when people search for your keywords.

#### Your Website URL
Having a site url which contains keywords you want to target also gives search engines a strong indication of what content may be on your site. Favour "pretty urls" over system generates ones. A url such as `http://anujnair.com/blog/seo-techniques` is much more descriptive than `http://anujnair.com/blog/id/12345`.

#### The Heading Tags
The header tags (H1 ... H6) have the next highest weighting from Google, H1 being the most important and descending in order. Try to limit the number of H1 tags you implement on a page, an make sure they are keyword rich. The other header tags should be used appropriately, holding as many keywords as they can in them. Don't try and cheat by putting whole paragraphs into header tags though - This will dilute their weighting which effectively means they will contribute nothing to your page ranking.

#### Smenatic HTML
Make sure that the HTML markup you have created is semantic and valid. Use services such as [The W3C HTML Validation Service](http://validator.w3.org/) to check this. Use `header` tags when you wish to express a header, use `nav` tags when you want to display navigation etc.

#### No Inline CSS
Try not to use inline CSS, or limit it if you have to. Search engines rank you on the content of your webpage, and if it is full of inline CSS, you are going to greatly decrease the weighting of all of your targeted keywords. Keep all CSS in separate files, and include that file into the head of the document. Not only does this help with your page ranking, but it also makes your work much more clean and easier to read and debug.

#### JavaScript Usage
Move as much JavaScript as possible into separate JavaScript files and include them into the head of your document. Minimize the amount of JavaScript that you use in your document; think to yourself every time you use JavaScript - is it really necessary to do it like this, or can I simply do this with HTML and CSS?

For example, never navigate to different webpages using the `onclick` event and  `window.location`. Why? Because Search Engine Spiders cannot follow these links, and usually look for the  `<a href=""></a>` hyperlink tags. Bots won't get to webpages deeper in your site, and you loose out on rankings.

Another example is using the `onMouseOver` JavaScript events - Use the pseudo class  `:hover` in CSS instead. It cuts down on the size of your HTML document, means your keywords won't be as diluted, and makes your code much easier to debug.

When using AJAX, make sure you always have a fall-back method if the user doesn't have JavaScript enabled. A lot of search engine spiders cannot handle AJAX, and so need these fall-back methods coded in simple PHP to be able to navigate your pages. Search for graceful degradation and progressive enhancement for more info.

#### Forms
Search engines never submit forms. If access to a specific part of your website is through a forum submission, a search engine will never reach that area.

#### Be aware of using HTML comments
HTML comments are still visible by search engines, and can again dilute the keywords in your page. The search engines may even start indexing your site based on what you have commented out on your page, which you probably don't want. Better to remove it completely, or use PHP commenting if available.

#### The Description Meta Tag
The description meta tag is usually used as a short blurb of your website - it is what people see listed under the title of your website on search engines, and usually plays a large part in attracting people into clicking on your links. It is mostly used by humans, and not the search engine bots though. Try and keep it about 150 characters as this is usually the cut of point on search engines.

#### The Keyword Meta Tag
Surprisingly to some, this contributes nothing to your page rank, as people abuse the meta tag, and so search engines were forced to decrease its weighting to produce more natural, relevant results. Google certainly do not use keywords to determine page rankings, but other search engines might, so fill them out honestly. Keep it concise (less than 10 keywords) otherwise your keywords will start to become diluted.

#### Analytics and Webmaster Tools
Finally, make sure you install Analytics on your page to track how your site is doing, and sign up to the Webmaster tools. [Google Analytics](http://google.com/analytics) is a great way to see how people are finding your website, what they are searching for to get to your page, their browser types and capabilities, and how popular your site actually is. From this data, you can adapt your website and utilize the data to increase your page rank. [Google Webmasters Tools](http://google.com/webmasters/) is another great service - From here you can see who is linking to you, and if the Google Spider has found any errors whilst browsing your website.


There are so many methods to help your page rank increase through the use of Search Engine Optimization - It's just a case of seeing what works and adapting to different situations.
