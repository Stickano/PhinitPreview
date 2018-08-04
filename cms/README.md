# Content Management System (CMS)
This is the administration tool for the PHPinit examples displayed in the client, as seen on [phpinit.com](https://phpinit.com).

### Requirements
Through this CMS you are able to create, update and delete examples. Have an overview of uploaded models, and remove them if necessary. You can edit the welcome message, shown before the examples, and lastly you get an overview of visits on the client site.

##### Creating Examples
There is a few formatting helpers in place when creating new examples. To be able and create a dynamic variety of examples and their preview, HTML is allowed.

Highlights (`<mark>`) can be achieved by encapsulating the key word(s) in grave accents (`). You could argue that it is pointless, given that HTML is allowed, but it does save keystrokes I guess.

To display the code boxes (`<pre><code>`) you can encapsulate the syntax in 3 single quotes (''') - Again just to save keystrokes.

Lastly, you are able to have PHP code evaluated in the example itself. So when creating examples, you can initialize the objects right there to display the functionality of each model. This is done by encapsulating the PHP code in 3 double quotes (""").