# Welcome to Post!

Using this API you can create new post, delete and modify existing posts.

The API is available here:


# Endpoints

Here's the list of all the endpoints and how to access them. All the data must be sent using **JSON** in the body of the request. 

## /api/posts

This endoint read all the posts using the **GET** method. There's no parameters.

Possible response:

- 200: All the post(s) will be returned in JSON format, using the following format
> "category_name":  The name of the category of the post. **String**.
"id": Unique id of the post. **Integer**.
"category_id": Id of the category of the post. **Integer**.
"title": Title of the post. **String**.
"body": Body of the post, **String**.
"author": Name of the author of the post, **String**.
"created_at": Date of the post, in the "**Y-m-d H:i:s**" format. The server is the **UTC** timezone.
- 404: Not found. There is no post to return.

## /api/post/create

This endoint creates a new post using the **POST** method. The parameter are:

- category_id
	> The category_id of the post. Integer.
- title
	> The title of the post. **String**..
- body
	> The body of the post. **String**..
- author
	> The name of the author of the post. **String**..
	
*NOTE*:  The date of creation of the post is automatically added to the post.

Possible response:

- 200: The post was successfully created.
- 404: You selected the wrong method.
- 500: There was an error in your request.
	
## /api/post/update/{id}

This endoint modify the title and body using the **PUT** method. You must provide an id.  The parameters are:

- title
	> Will update the title of the post. **String**.
- body
	> Will update the body of the post. **String**..

Possible response:

- 200: The post was successfully updated.
- 404: The request lacks the ID, or you selected the wrong method.
- 500: There was an error in your request.

## /api/post/update_title/{id}

This endoint modify the title using the **PATCH** method. You must provide an id.  The parameter is:

- title
	> Will update the title of the post. **String**..

Possible response:

- 200: The post was successfully updated.
- 404: The request lacks the ID, or you selected the wrong method.
- 500: There was an error in your request.

## /api/post/delete/{id}

This endoint delete the post using the **DELETE** method. You must provide an id.  There's no parameters.

Possible response:

- 200: The post was successfully deleted.
- 404: The request lacks the ID, or you selected the wrong method.
- 500: There was an error in your request.

## Error 404 - Not found

If you end up with an error 404, it could be because:

- The route is invalid. A route with a typo will return an error 404.
- You provided the request with an invalid or missing id (if required). Check the documentation to make sure you provided the request with necessary data.
- The method is incorrect. Check the documentation to make sure you provided the request with the proper method.