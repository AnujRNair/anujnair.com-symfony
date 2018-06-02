# Cache Design Patterns: Namespacing and Cache Expiration

Memcached is a caching daemon used in PHP to speed up dynamic data driven applications by store objects and data in memory; it's main use is to reduce the number of times we have to recall these objects from external data sources (e.g. DBs and APIs), as reading from these sources is generally slower than reading from memory.

However, on large scale applications, it's not trivial to implement a good caching system, usually because of invalidation issues. When a user performs an action to update data, we want to make sure this is reflected in the cache as well so the new data displays across all areas of the site. That means we'll have issues if we've cached the data under multiple keys.

#### Proposed Solution 1: Namespacing and cache expiration

Since cache keys can be pretty long (250 chars), if we build our cache keys in an intelligent way, we can force "invalidation" of objects by changing key values. Actually, we're just cleverly building a new key name, and letting the old value expire

Here's some pseudo code for a user example:
```php
// Simple function to get post for a user
public function getUserPost(userId, $postId) {
    $sql = "select *
            from tb_post
            inner join tb_user
            on tb_post.user_id = tb_user.user_id
            where tb_post.post_id = :postId
            and tb_user.user_id = :userId"

    // Get keys from the cache. If it doesn't exit, set it as 0
    $userKey = $memcache->get("userId_" . $userId) || 0;
    $postKey = $memcache->get("postId_" . $postId) || 0;
    $queryKey = "cache_getUserPost_userId{$userId}_{$userKey}_postId{$postId}_{$postKey}";

    // Try getting result from cache - if not, get from DB and store in cache
    $result = $memcache->get($queryKey);
    if (!$result) {
        $result = $sql->prepare()->execute();
        $memcache->set($queryKey, $result, 0, DEFAULT_CACHE_TIME);
    }

    return $result;
}

// Simple function to update user details
public function updateUserDetails($userId, $fname, $lname) {
    $sql = "update tb_user
            set
                fname = :fname,
                lname = :lname
            where user_id = :userId";

    // Get key from cache. You'll notice this generates the key in exactly the same way as the function above.
    $userKey = $memcache->get("userId_" . $userId);

    // Update user details
    $result = $sql->prepare()->execute();
    if ($result) {
        // If updated, change the user key number for this specific user
        if ($userKey) {
            $memcache->increment($userKey);
        } else {
            $memcache->set($userKey, 0);
        }
    }

    // Updated details?
    return result;
}
```

Imagine the following use case example:


* User 1 hits the front page of your blog - their details, and their post details (post 1) are loaded and cached.
* `userId_1 = 0` in the cache, and `postId_1 = 0` in the cache. That means the queryKey in the first function will be: `cache_getUserPost_userId1_0_postId1_0`.
* Your user then updates their details.
* The value for `userId_1` in the cache is incremented to 1
* Your user visits the front page again.
* The cache key has now changed to `cache_getUserPost_userId1_1_postId1_0`. Since this doesn't exist in the cache, the results will be obtained from the DB and re-cached under a different key, essentially "invalidating" the old result set.

You'll probably want to create a standardised way of creating simple cache keys to avoid mistakes as well.

**Pros:**

* Allows you to invalidate anything related to a specific model very easily. Effectively, this allows for "wildcard" deletion. I could cache user details in multiple places - as long as all of those result sets contain the user key in the cache key, when I change the key, all of those results will become "invalidated"

**Cons:**

* Have to cache simple objects based on models - Generating keys and invalidating results for the last 10 blog posts, for example, might be a bit more challenging (maybe select and cache individually)

Let me know what you think!
