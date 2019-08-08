## Landscape Institute onDemand (LIOD)

### Requirements
- Woocommerce => 3.6.5
- Compatible theme

### Recommended
To allow for MyLI oAuth Login and Authentication
- MyLI WP 
- MyLI WP Authentication

### Introduction

LIOD is a simple ondemand plugin which allows you to create events, create videos, link the videos to the event, link the event to a woocommerce product and for the purchase of that product to allow access to the given video page. 

LIOD also has the option of subscription products, which have a time limit applied for their validity. During which every event is considered purchased. 

Videos for purchased events will display a vimeo iframe so customers can view the video. 

### Setting up an Event

Setting up a new event is merely done by first creating the product in Woocommerce that needs to be purchased to give access. 

Next setup a new event and ensure you link the product you just created to that event. Completing any additional information about that event. 

Next create your videos which will sit under that event. You must link that video to your previously using the new video form. 

This leaves you with a linked order of posts 

` Product -> Event -> Video`

## Technical Details

### Custom Post Types

LIOD adds two custom post types
- Events
- Videos

### Custom Product Type

LIOD adds 2 new custom product type
- Subscription
- Event

Subscriptions has an additional tab for specifying how long the subscription lasts

### Template WP_QUERY style functions

Within post loops, generally inside a template on a singles page or archive page you can simple functions provided by wordpress to query the current POST. 
In the same manner these functions can be used with WP Query loops. 
For example in simular places where you could use 

`post_title()`
`get_the_id()` 


| Function | Returns  | Action |
|--|--|--|
| `liod_is_video` | BOOL is this POST a video | None |
| `liod_is_event` | BOOL is this POST an event | None |
| `liod_get_the_event` | EVENT | None |
| `liod_get_the_event_category` | STRING | None |
| `liod_the_event_category` | None | ECHO Event Category|
| `liod_get_the_event_topics` | ARRAY STRING | None |
| `liod_get_the_event_payment_model` | STRING | None |
| `liod_the_event_payment_model` | None | ECHO event payment model |
| `liod_get_the_event_type` | STRING | None |
| `liod_the_event_type` | None | ECHO event type |
| `liod_get_the_event_date` | DATETIME | None |
| `liod_the_event_date` | NONE | ECHO event date |
| `liod_get_the_event_location` | STRING| None |
| `liod_the_event_location` | None  | ECHO event location|
| `liod_event_is_free` | BOOL | None |
| `liod_event_has_videos` | BOOL | None


