# Client

This is the website of [phpinit.com](https://phpinit.com). It is a fairly simple website, build to be practical and focuses on displaying the functionality of the PHPinit models.

### 3rd party
This client-side website uses [Highlight Js](https://highlightjs.org) for syntax highlight in the code preview boxes. Much love.

### Requirements
This website had to fetch examples from a database, format the output so it would be presented nicely, i.e. with use of code boxes, and evaluate the PHP code for those examples.

The headlines is used as bookmark ids, making it easy to jump between examples.

Next to the headline of each example is a "download" button, referring to the model on Github.

##### The Examples
When these examples are created by the [CMS](../cms), a few formatting rules has been introduced. To make it easy and manipulate the output of these examples, for a dynamic variety of functionality, this client view is decided to accept pure html when writing the examples.

It also allows one to encapsulate clean PHP code inside 3 double quotes, to have that portion evaluated and executed.

Lastly it allows for displaying syntax in `<pre>` & `<code>` elements if encapsulated in 3 single quotes.

### NSA
Data gathered by the [Client model](https://phpinit.com/#Client) will be stored in a database table called `nsa`. We will gather that client information and use it for absolutely nothing!