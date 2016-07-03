## What's it all about?

The purpose of the project was to create a syntactically simple way to implement context-based user access control. What does that mean, exactly? Good question...

### Context-based access control

I wanted to start with the idea that I could use a really straight-forward syntax for my "things" (whatever they might be). The first concept I came up with was `Check::can('post.edit')`. Because I was a fan of naming my routes, this made good sense from a flow point-of-view. Because I have my routes named, I figured I'd be able to implement middleware that would also leverage the access control system.

Adding context to the access control wasn't a trivial task. Each model will have its own context. Say in a `Post` model, "owning" a post might mean that there is a `user_id` field on the `Post` that is equal to the current user. So, how do I have a simple syntaxt for implementing and checking permissions, but also giving context when the need arises?