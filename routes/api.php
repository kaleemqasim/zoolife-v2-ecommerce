<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('disclaimer', 'Admin\AccountController@updatedescliamer');

Route::post('articles', 'Admin\ArticleController@api_articles');
Route::post('article', 'Admin\ArticleController@api_single_article');
Route::post('sliders', 'Admin\SliderController@api_sliders');

// items Api
Route::post('reportapi', 'Api\ItemController@reportApi_ads');
Route::post('get_by_item_report', 'Api\ItemController@get_by_item_report');
Route::get('itemsapi', 'Api\ItemController@items');
Route::post('deliveries', 'Api\ItemController@get_all_delivery');
Route::post('get_all_item_by_category', 'Api\ItemController@get_all_item_by_category');
Route::post('get_item', 'Api\ItemController@get_item');
Route::post('get_post_by_user', 'Api\ItemController@get_post_by_user');
Route::post('deactivate_item', 'Api\ItemController@deactivate_item');
Route::post('activate_item', 'Api\ItemController@activate_item');
Route::post('delete_item', 'Api\ItemController@delete_item');
Route::post('delete_deliver', 'Api\ItemController@delete_deliver');
Route::post('delete_item_images', 'Api\ItemController@delete_item_images');
Route::post('add_post', 'Api\ItemController@add_post');
Route::post('update_post', 'Api\ItemController@update_post');
Route::post('add_delivery', 'Api\ItemController@add_delivery');
Route::post('list_likes', 'Api\ItemController@list_likes');
Route::post('like_item', 'Api\ItemController@like_item');
Route::post('delete_like_item', 'Api\ItemController@delete_like_item');
Route::post('abuse_item', 'Api\ItemController@abuse_item');
Route::post('list_abused_items', 'Api\ItemController@list_abused_items');
Route::post('delete_abused_item', 'Api\ItemController@delete_abused_item');
Route::post('favoruit_item', 'Api\ItemController@favoruit_item');
Route::post('fav_item_by_user', 'Api\ItemController@favoruit_list_item_by_user');
Route::post('favoruit_list_by_item', 'Api\ItemController@favoruit_list_by_item');
Route::post('delete_favorites', 'Api\ItemController@delete_favorites');
Route::post('item_search', 'Api\ItemController@item_search');
Route::post('auction_items', 'Api\ItemController@getAllAuctionPosts');
Route::post('get_items_by_subcategory', 'Api\ItemController@itemsBySubcategory');
Route::post('get_items_by_city', 'Api\ItemController@itemsByCity');


Route::post('get_chat_list_by_user', 'Admin\ChatController@get_chat_list_by_user');
Route::post('get_single_chat_by_user', 'Admin\ChatController@get_single_chat_by_user');
Route::post('send_message', 'Admin\ChatController@send_message');
Route::post('send_new_message', 'Admin\ChatController@send_new_message');
Route::post('delete_message', 'Admin\ChatController@delete_message');


Route::post('add_comments', 'Admin\CommentsController@add_comments');
Route::post('delete_comment', 'Admin\CommentsController@delete_comment');
Route::post('list_comments_by_item', 'Admin\CommentsController@list_comments_by_item');

Route::post('registerapi', 'Admin\AccountController@registerapi');
Route::post('verify_otp', 'Admin\AccountController@verify_otp');
Route::post('resend_otp', 'Admin\AccountController@resend_otp');
Route::post('loginapi', 'Admin\AccountController@loginapi');
Route::post('reset_password', 'Admin\AccountController@reset_password');
Route::post('update_password', 'Admin\AccountController@update_password');

Route::post('add_notifications', 'Admin\NotificationController@api_notification');

Route::post('add_msgs', 'Admin\NotificationController@add_msg_badge');

Route::post('read_msg', 'Admin\NotificationController@read_msg_badge');

Route::post('get_all_notify', 'Admin\NotificationController@get_all_noti_by_userid');
Route::post('category', 'Admin\CategoryController@api_category');
Route::post('get_sub_category', 'Admin\CategoryController@get_sub_category');
Route::post('get_single_category', 'Admin\CategoryController@get_single_category');

Route::post('get_user_profile', 'Admin\UserController@get_user_profile');
Route::post('update_user_profile', 'Admin\UserController@update_user_profile');
Route::post('update_user_device_token', 'Admin\UserController@update_user_device_token');
Route::get('cities', 'Api\ItemController@cities');
Route::post('add_bid', 'Api\ItemController@addBid');
Route::post('is_user_bid', 'Api\ItemController@alreadyBidByUser');
Route::post('bids', 'Api\ItemController@getAllBidsOfPost');






//v2 apis
Route::prefix('v2')->group(function () {
    Route::post('disclaimer', 'Admin\AccountController@updatedescliamer');
    
    Route::post('articles', 'Admin\ArticleController@api_articles');
    Route::post('article', 'Admin\ArticleController@api_single_article');
    Route::post('sliders', 'Admin\SliderController@api_sliders');
    
    // items Api
    Route::post('reportapi', 'Api\Item2Controller@reportApi_ads');
    Route::post('get_by_item_report', 'Api\Item2Controller@get_by_item_report');
    Route::get('itemsapi', 'Api\ItemV2Controller@items');
    Route::post('deliveries', 'Api\ItemV2Controller@get_all_delivery');
    Route::post('get_all_item_by_category', 'Api\ItemV2Controller@get_all_item_by_category');
    Route::post('get_item', 'Api\ItemV2Controller@get_item');
    Route::post('get_post_by_user', 'Api\ItemV2Controller@get_post_by_user');
    Route::post('deactivate_item', 'Api\ItemV2Controller@deactivate_item');
    Route::post('activate_item', 'Api\ItemV2Controller@activate_item');
    Route::post('delete_item', 'Api\ItemV2Controller@delete_item');
    Route::post('delete_deliver', 'Api\ItemV2Controller@delete_deliver');
    Route::post('delete_item_images', 'Api\ItemV2Controller@delete_item_images');
    Route::post('add_post', 'Api\ItemV2Controller@add_post');
    Route::post('update_post', 'Api\ItemV2Controller@update_post');
    Route::post('add_delivery', 'Api\ItemV2Controller@add_delivery');
    Route::post('list_likes', 'Api\ItemV2Controller@list_likes');
    Route::post('like_item', 'Api\ItemV2Controller@like_item');
    Route::post('delete_like_item', 'Api\ItemV2Controller@delete_like_item');
    Route::post('abuse_item', 'Api\ItemV2Controller@abuse_item');
    Route::post('list_abused_items', 'Api\ItemV2Controller@list_abused_items');
    Route::post('delete_abused_item', 'Api\ItemV2Controller@delete_abused_item');
    Route::post('favoruit_item', 'Api\ItemV2Controller@favoruit_item');
    Route::post('fav_item_by_user', 'Api\ItemV2Controller@favoruit_list_item_by_user');
    Route::post('favoruit_list_by_item', 'Api\ItemV2Controller@favoruit_list_by_item');
    Route::post('delete_favorites', 'Api\ItemV2Controller@delete_favorites');
    Route::post('item_search', 'Api\ItemV2Controller@item_search');
    Route::post('auction_items', 'Api\ItemV2Controller@getAllAuctionPosts');
    Route::post('get_items_by_subcategory', 'Api\ItemV2Controller@itemsBySubcategory');
    Route::post('get_items_by_city', 'Api\ItemV2Controller@itemsByCity');
    
    
    Route::post('get_chat_list_by_user', 'Admin\ChatController@get_chat_list_by_user');
    Route::post('get_single_chat_by_user', 'Admin\ChatController@get_single_chat_by_user');
    Route::post('send_message', 'Admin\ChatController@send_message');
    Route::post('send_new_message', 'Admin\ChatController@send_new_message');
    Route::post('delete_message', 'Admin\ChatController@delete_message');
    
    
    Route::post('add_comments', 'Admin\CommentsController@add_comments');
    Route::post('delete_comment', 'Admin\CommentsController@delete_comment');
    Route::post('list_comments_by_item', 'Admin\CommentsController@list_comments_by_item');
    
    Route::post('registerapi', 'Admin\AccountV2Controller@registerapi');
    Route::post('verify_otp', 'Admin\AccountV2Controller@verify_otp');
    Route::post('resend_otp', 'Admin\AccountV2Controller@resend_otp');
    Route::post('loginapi', 'Admin\AccountV2Controller@loginapi');
    Route::post('reset_password', 'Admin\AccountV2Controller@reset_password');
    Route::post('update_password', 'Admin\AccountV2Controller@update_password');
    
    Route::post('add_notifications', 'Admin\NotificationController@api_notification');
    
    Route::post('add_msgs', 'Admin\NotificationController@add_msg_badge');
    
    Route::post('read_msg', 'Admin\NotificationController@read_msg_badge');
    
    Route::post('get_all_notify', 'Admin\NotificationController@get_all_noti_by_userid');
    Route::post('category', 'Admin\CategoryController@api_category');
    Route::post('get_sub_category', 'Admin\CategoryController@get_sub_category');
    Route::post('get_single_category', 'Admin\CategoryController@get_single_category');
    
    Route::post('get_user_profile', 'Admin\UserController@get_user_profile');
    Route::post('update_user_profile', 'Admin\UserController@update_user_profile');
    Route::post('update_user_device_token', 'Admin\UserController@update_user_device_token');
    Route::get('cities', 'Api\ItemV2Controller@cities');
    Route::post('add_bid', 'Api\ItemV2Controller@addBid');
    Route::post('is_user_bid', 'Api\ItemV2Controller@alreadyBidByUser');
    Route::post('bids', 'Api\ItemV2Controller@getAllBidsOfPost');
    
    Route::get('user/{id}/posts', 'Api\ItemV2Controller@getUserPosts');
    Route::post('mark-notification-read', 'Admin\NotificationController@markNotificationRead');
    Route::post('delete-notification', 'Admin\NotificationController@deleteNotification');

});
