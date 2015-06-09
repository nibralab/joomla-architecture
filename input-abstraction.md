---
layout: default
title: Input Abstraction
category: Frontend
author: Niels Braczek
author_email: nbraczek@bsds.de
---

## Input Abstraction

For many operations, an ID is needed. These IDs usually are numbers, and thus hard to remember for humans.
Therefore, whenever a data record has a unique alias (like `user_id` - `user_name`), the alias can be used instead of
the numeric ID.
 
The table below shows some use cases over different channels.
The **Web** column shows the requests from current Joomla 3.4.1


<table>
    <tr>
        <th></th>
        <th>CLI</th>
        <th>REST</th>
        <th>Web</th>
    </tr>
    <tr>
        <td>
            <h4>Get a list of users</h4>
        </td>
        <td><pre>
$ joomla users show
        </pre></td>
        <td><pre>
GET /users HTTP/1.1
Host: example.com
Accept: application/xml

        </pre></td>
        <td><pre>
GET index.php?option=com_users&amp;view=users HTTP/1.1
Host: example.com
Accept: text/html
        </pre></td>
    </tr>
    <tr>
        <td>
            <h4>Get details of a user</h4>
        </td>
        <td><pre>
$ joomla users show --id=1234
        </pre></td>
        <td><pre>
GET /users/1234 HTTP/1.1
Host: example.com
Accept: application/xml

        </pre></td>
        <td><pre>
GET index.php?option=com_users&amp;view=user&amp;id=1234 HTTP/1.1
Host: example.com
Accept: text/html
        </pre>
        (in theory - there is no detail view for users in 3.4)
        </td>
    </tr>
    <tr>
        <td>
            <h4>Add a user note</h4>
        </td>
        <td><pre>
$ joomla user-notes add --user-id=1234 \
    --subject="..." \
    --body="..." \
    --catid=7 \
    --review-time="..." \
    --version-note="..."
        </pre></td>
        <td><pre>
POST /users/1234/notes HTTP/1.1
Host: example.com
Accept: application/xml
Content-Type: application/xml
Content-Length: ...

&lt;user-note>
    &lt;subject>&lt;![CDATA[...]]>&lt;/subject>
    &lt;catid>7&lt;/catid>
    &lt;review_time>...&lt;/review_time>
    &lt;version_note>...&lt;/version_note>
    &lt;body>&lt;![CDATA[...]]>&lt;/body>
&lt;/user-note>
        </pre></td>
        <td><pre>
POST /index.php?option=com_users&amp;view=note&amp;id=0 HTTP/1.1
Host: example.com
Accept: text/html
Content-Type: application/x-www-form-urlencoded
Content-Length: ...

jform%5Bsubject%5D=...&amp;jform%5Buser_id%5D=1234&amp;jf
orm%5Bcatid%5D=7&amp;jform%5Bstate%5D=1&amp;jform%5Brevie
w_time%5D=&amp;jform%5Bversion_note%5D=&amp;jform%5Bbody%
5D=...&amp;task=note.save&amp;789ab4e5a25391f60c435dcced5
40c1c=1
        </pre></td>
    </tr>
    <tr>
        <td>
            <h4>Delete a user note</h4>
        </td>
        <td><pre>
$ joomla user-notes delete --id=567
        </pre></td>
        <td><pre>
DELETE /users/1234/notes/567 HTTP/1.1
Host: example.com
Accept: application/xml

        </pre></td>
        <td><pre>
POST /administrator/index.php?option=com_users&view=notes HTTP/1.1
Host: example.com
Accept: text/html
Content-Type: application/x-www-form-urlencoded
Content-Length: ...

filter_published=&amp;filter_category_id=&amp;filter_sear
ch=&amp;limit=20&amp;directionTable=&amp;sortTable=a.review_t
ime&amp;checkall-toggle=&amp;limitstart=0&amp;cid=&amp;task=notes
.trash&amp;boxchecked=1&amp;filter_order=a.review_time&amp;fi
lter_order_Dir=DESC&amp;789ab4e5a25391f60c435dcced540
c1c=1
        </pre></td>
    </tr>
</table>    

### Routing

For most of the requests, the **REST** and **Web** requests should only differ in the `Accept` header field
(and the `Content-Type`) to make routing much more handy.
For search engine optimizations (SEO), articles can contain their own routing information in the `alias` field.
If an `alias` starts with a slash, it is used un-prefixed.
Otherwise, the category `alias` is set in front of it recursively, until a category has a leading slash or is a root category.
The site owner is responsible for avoiding conflicts. 

### Redirection

Any redirection, which currently is initiated by the server, should be handled on the client's side.
Since the response contains all relevant links, it should be easy for the client to redirect itself to the right page.

### Input Definitions

In all cases, the input definition comes from the model's form description, so it should be possible to generate them automatically.
