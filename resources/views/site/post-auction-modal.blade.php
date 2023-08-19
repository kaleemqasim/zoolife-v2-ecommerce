<div class="modal-header pt-45">
    @if($data['type'] == 'auction')
    <h5 class="modal-title">{{ __('Add New Auction') }}</h5>
    @else
    <h5 class="modal-title">{{ __('Add New Post') }}</h5>
    @endif
</div>
<div class="modal-body">
    <form id="savePostForm">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="upload-container">
                <div class="image-preview">
                    <img src="{{asset('uploads/ad/'.($post->imgUrl ?? ''))}}" alt="">
                    <div class="img-btns">
                        <!-- <span class="" >{{ __('Add Image') }}</span> -->
                        <input type="file" name="imgUrl" onchange="previewImage($(this), 'image')">
                    </div>
                    @if(!empty($post->imgUrl))
                    <input type="hidden" name="old_imgUrl" id="image_hidden" class="img_name" value="{{$post->imgUrl}}" />
                    @endif
                </div>
                <span class="" >{{ __('Add Image') }}</span>
                <div id="progress" class=""></div>
            </div>
            <div class="upload-container">
                <div class="image-preview">
                    @if(!empty($post->images[0]->id))
                    <button class="btn btn-danger remove-post-img" data-type="remove_image" data-id="{{$post->images[0]->id}}" data-item="{{$post->id}}"><i class="las la-times"></i></button>
                    @endif
                    <button type="button" class="btn btn-danger remove-img-btn d-none"><i class="las la-times"></i></button>
                    <img src="{{$post->images[0]->file_name ?? ''}}" alt="">
                    <div class="img-btns">
                        <!-- <span class="" >{{ __('Add Image') }}</span> -->
                        <input type="file" name="images[]" onchange="previewImage($(this), 'image')">
                    </div>
                </div>
                <span class="" >{{ __('Add Image') }}</span>
            </div>
            <div class="upload-container">
                <div class="image-preview">
                    @if(!empty($post->images[1]->id))
                    <button class="btn btn-danger remove-post-img" data-type="remove_image" data-id="{{$post->images[1]->id}}" data-item="{{$post->id}}"><i class="las la-times"></i></button>
                    @endif
                    <button type="button" class="btn btn-danger remove-img-btn d-none"><i class="las la-times"></i></button>
                    <img src="{{$post->images[1]->file_name ?? ''}}" alt="">
                    <div class="img-btns">
                        <!-- <span class="" >{{ __('Add Image') }}</span> -->
                        <input type="file" name="images[]" onchange="previewImage($(this), 'image')">
                    </div>
                </div>
                <span class="" >{{ __('Add Image') }}</span>
            </div>
            <div class="upload-container">
                <div class="image-preview">
                    @if(!empty($post->images[2]->id))
                    <button class="btn btn-danger remove-post-img" data-type="remove_image" data-id="{{$post->images[2]->id}}" data-item="{{$post->id}}"><i class="las la-times"></i></button>
                    @endif
                    <button type="button" class="btn btn-danger remove-img-btn d-none"><i class="las la-times"></i></button>
                    <img src="{{$post->images[2]->file_name ?? ''}}" alt="">
                    <div class="img-btns">
                        <!-- <span class="" >{{ __('Add Image') }}</span> -->
                        <input type="file" name="images[]" onchange="previewImage($(this), 'image')">
                    </div>
                </div>
                <span class="" >{{ __('Add Image') }}</span>
            </div>
            <div class="upload-container">
                <div class="image-preview">
                    @if(!empty($post->images[3]->id))
                    <button class="btn btn-danger remove-post-img" data-type="remove_image" data-id="{{$post->images[3]->id}}" data-item="{{$post->id}}"><i class="las la-times"></i></button>
                    @endif
                    <button type="button" class="btn btn-danger remove-img-btn d-none"><i class="las la-times"></i></button>
                    <img src="{{$post->images[3]->file_name ?? ''}}" alt="">
                    <div class="img-btns">
                        <!-- <span class="" >{{ __('Add Image') }}</span> -->
                        <input type="file" name="images[]" onchange="previewImage($(this), 'image')">
                    </div>
                </div>
                <span class="" >{{ __('Add Image') }}</span>
            </div>
            <div class="error text-danger" id="imgUrl_error"></div>
        </div>
        @if($data['type'] == 'auction')
        <div class="col-md-12 mb-4">
            <div class="upload-container">
                <div class="image-preview">
                    <video src="{{asset('uploads/ad_video/'.($post->videoUrl ?? ''))}}" alt=""></video>
                    <div class="img-btns">
                        <!-- <span class="" >{{ __('Add video') }}</span> -->
                        <input type="file" name="videoUrl" onchange="previewImage($(this), 'video')">
                    </div>
                    @if(!empty($post->videoUrl))
                    <input type="hidden" name="old_videoUrl" id="video_hidden" class="img_name" value="{{$post->videoUrl}}" />
                    @endif
                </div>
                <span class="" >{{ __('Add video') }}</span>
                <div id="progress" class=""></div>
            </div>
            <div class="error text-danger" id="videoUrl_error"></div>
        </div>
        @endif
    </div>
    <div class="row">
        <input type="hidden" name="id" value="{{$post->id ?? ''}}">
        <input type="hidden" name="post_type" value="{{$data['type'] ?? ''}}">
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="popup-form-field">
                <label for="">{{ __('Add Location') }}</label>
                <select id="city" name="city">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($cities as $city)
                    <option value="{{$city->name}}" {{(!empty($post->city) && ($post->city == $city->name)) ? 'selected' : ''}}>{{$city->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="popup-form-field">
                <label for="age">{{ __('Age') }}</label>
                <input type="text" name="age" value="{{$post->age??''}}" placeholder="{{ __('Enter here') }}">
            </div>
            <div class="error text-danger" id="age_error"></div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="popup-form-field">
                <label for="category">{{ __('Choose Category') }}</label>
                <select id="category" name="category">
                    <option value="">{{ __('Select') }}</option>
                    @foreach ($categories as $category)
                    <option value="{{$category->id}}" {{(!empty($post->category) && ($post->category == $category->id)) ? 'selected' : ''}}>{{$category->title}}</option>
                    @endforeach
                </select>
            </div>
            <div class="error text-danger" id="category_error"></div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-30" >
            <label for="subCategory">{{ __('Choose Subcategory') }}</label>
            <div class="form-group" style="position: relative;">
                <select id="sub_category" name="subCategory"  class="form-control ">
                    @if(!empty($post->subCategory))
                    <option value="{{$post->subCategory ?? ''}}">{{$post->sub_category ?? ''}}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="popup-form-field">
                <label for="">{{ __('Add Post Title') }}</label>
                <input type="text" name="itemTitle" value="{{$post->itemTitle??''}}" placeholder="{{ __('Enter here') }}">
            </div>
            <div class="error text-danger" id="itemTitle_error"></div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="popup-form-field">
                <label for="vaccine_detail">{{ __('Vaccine Details') }}</label>
                <input type="text" name="vaccine_detail" value="{{$post->vaccine_detail??''}}" placeholder="{{ __('Enter here') }}">
            </div>
        </div>
        @if(!empty($data['type']) && $data['type'] == 'auction')
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="popup-form-field">
                <label for="">{{ __('Add Starter Price') }}</label>
                <input type="text" name="min_bid" placeholder="{{ __('Enter here') }}" value="{{$post->min_bid ?? ''}}">
                <div class="error text-danger" id="min_bid_error"></div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
            <div class="popup-form-field">
                @php $days = [1,2,3,4,5,6,7] @endphp
                <label for="">{{ __('Total Days') }}</label>
                <select id="" name="days">
                    <option value="">Select Day</option>
                    @foreach ($days as $day)
                    <option value="{{$day}}" {{(!empty($post->expiry_days) && ($post->expiry_days == $day)) ? 'selected' : ''}}>{{$day}}</option>
                    @endforeach
                </select>
                <div class="error text-danger" id="expiry_days_error"></div>
            </div>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-6 mb-30">
            <div class="popup-form-field">
                <label for="">{{ __('Hours') }}</label>
                @php $hours = [1,2,3,4,5,6,7,8,9,10,11,12] @endphp
                <select id="" name="hours">
                    <option value="">Select Hours</option>
                    @foreach ($hours as $hour)
                    <option value="{{$hour}}" {{(!empty($post->expiry_hours) && ($post->expiry_hours == $hour)) ? 'selected' : ''}}>{{$hour}}</option>
                    @endforeach
                </select>
                <div class="error text-danger" id="hours_error"></div>
            </div>
        </div>
        @endif
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="radio-option">
                <label for="">{{ __('Sex') }}</label>
                <div class="d-flex align-items-center gap-5">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="female" value="female" {{(!empty($post->sex) && ($post->sex == 'female')) ? 'checked' : ''}}>
                        <label class="form-check-label" for="female">{{ __('Female') }}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="sex" id="male" value="male" {{(!empty($post->sex) && ($post->sex == 'male')) ? 'checked' : ''}}>
                        <label class="form-check-label" for="male">{{ __('Male') }}</label>
                    </div>
                </div>
            </div>
            <div class="error text-danger" id="sex_error"></div>
        </div>
        <div class="col-xl-6 col-lg-6 mb-30">
            <div class="radio-option">
                <label for="passport">{{ __('Passport') }}</label>
                <div class="d-flex align-items-center gap-5">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="passport" id="pass-yes" value="yes" {{(!empty($post->passport) && ($post->passport == 'yes')) ? 'checked' : ''}}>
                        <label class="form-check-label" for="pass-yes">{{__('Yes')}}</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="passport" id="pass-no" value="no" {{(!empty($post->passport) && ($post->passport == 'no')) ? 'checked' : ''}}>
                        <label class="form-check-label" for="pass-no">{{__('No')}}</label>
                    </div>
                </div>
            </div>
            <div class="error text-danger" id="passport_error"></div>
        </div>
        <div class="col-12 mb-30">
            <div class="popup-form-field">
                <label for="itemDesc">{{ __('Post Description') }}</label>
                <textarea cols="10" name="itemDesc" rows="5" placeholder="{{ __('Enter here') }}">{{$post->itemDesc??''}}</textarea>
            </div>
        </div>
        @if(!empty($data['type']) && $data['type'] == 'normal')
        <div class="radio-option mb-30">
            <label for="">{{ __('Select Communications options:') }}</label>
            <div class="communication-options">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="showPhoneNumber" id="showPhoneNumber" value="1" {{(!empty($post->showPhoneNumber) && ($post->showPhoneNumber == 1)) ? 'checked' : ''}}>
                    <label class="form-check-label" for="showPhoneNumber">{{ __('Phone') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="showMessage" id="showMessage" value="1" {{(!empty($post->showMessage) && ($post->showMessage == 1)) ? 'checked' : ''}}>
                    <label class="form-check-label" for="showMessage">{{ __('Message') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="showComments" id="showComments" value="1" {{(!empty($post->showComments) && ($post->showComments == 1)) ? 'checked' : ''}}>
                    <label class="form-check-label" for="showComments">{{ __('Comments') }}</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="showWhatsapp" id="showWhatsapp" value="1" {{(!empty($post->showWhatsapp) && ($post->showWhatsapp == 1)) ? 'checked' : ''}}>
                    <label class="form-check-label" for="showWhatsapp">{{ __('WhatsApp') }}</label>
                </div>
            </div>
        </div>
        @endif
    </div>
    </form>
</div>
<div class="modal-footer pb-35">
    <div id="post_submit_notification"></div>
    <button type="button" class="btn theme-btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    <button type="button" class="btn theme-btn" id="savePost" data-url="{{route('save-post')}}">{{ __('Post') }}</button>
</div>
<script type="text/javascript">

    function previewImage(ele, type){
        console.log(type);
        const [file] = ele[0].files;
        // $('#upload-img').attr('src',URL.createObjectURL(file));
        if (type == 'video') {
            // $(".video-preview .single-img").html(`<video src="`+URL.createObjectURL(file)+`" style="width: 100%; height: 50px;" />`);
            ele.closest(".image-preview").find('video').attr("src", URL.createObjectURL(file));
        } else {
            // let html = `<div class="single-img">
            //                 <input type="hidden" name="images[]" value="`+file+`"/>
            //                 <img src="`+URL.createObjectURL(file)+`" alt="">
            //                 <span class="remove-img-btn"><i class="las la-times"></i></span>
            //             </div>`;
            console.log(URL.createObjectURL(file));
            ele.closest(".image-preview").find('img').attr("src", URL.createObjectURL(file));
        }
        ele.closest(".image-preview").find('.remove-img-btn').removeClass('d-none');
        $('.show-uploaded-img').show();
    }

    @if(!empty($post->subCategory))
        loadSubCategory({{$post->subCategory ?? ''}});
    @endif

    $('#category').on('change', function() {
        loadSubCategory();
    });
</script>

