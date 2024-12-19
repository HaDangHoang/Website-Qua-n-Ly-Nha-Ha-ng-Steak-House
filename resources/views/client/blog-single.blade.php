@extends('client.layouts.master')
@section('title', 'Blog')

@section('content')
    @include('client.layouts.component.subheader', [
        'backgroundImage' => 'client/03_images/background/bg-6.jpg',
        'subtitle' => 'Blog',
        'title' => '10 Reasons To Drink Coffee Every Day',
        'currentPage' => 'Blog',
        'blog' => '10 Reasons To Drink Coffee Every Day',
    ])
    <div id="content" class="no-bottom no-top">
        <section id="section-book-form">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 offset-lg-2">
                        <div class="de-post-read">
                            <div class="post-content">

                                <div class="post-text">
                                    <div class="row g-4 zoom-gallery mb-4">
                                        <div class="col-lg-6">
                                            <figure class="hover-zoom position-relative overflow-hidden">
                                                <a href="client/images/gallery/1.jpg">
                                                    <span class="d-hover">
                                                        <span class="d-text">
                                                            <span class="d-cap">View</span>
                                                        </span>
                                                    </span>
                                                    <img src="client/03_images/gallery/1.jpg" alt="">
                                                </a>
                                            </figure>
                                        </div>

                                        <div class="col-lg-6">
                                            <figure class="hover-zoom position-relative overflow-hidden">
                                                <a href="client/images/gallery/3.jpg">
                                                    <span class="d-hover">
                                                        <span class="d-text">
                                                            <span class="d-cap">View</span>
                                                        </span>
                                                    </span>
                                                    <img src="client/03_images/gallery/3.jpg" alt="">
                                                </a>
                                            </figure>
                                        </div>
                                    </div>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt
                                        ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat. Lorem ipsum dolor
                                        sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                        reprehenderit in voluptate velit esse cillum dolore eu fugiat. Lorem ipsum dolor sit
                                        amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat.</p>

                                    <div class="row g-4 zoom-gallery mb-4">
                                        <div class="col-lg-6">
                                            <figure class="hover-zoom position-relative overflow-hidden">
                                                <a href="client/images/misc/6.jpg">
                                                    <span class="d-hover">
                                                        <span class="d-text">
                                                            <span class="d-cap">View</span>
                                                        </span>
                                                    </span>
                                                    <img src="client/03_images/misc/6.jpg" alt="">
                                                </a>
                                            </figure>
                                        </div>

                                        <div class="col-lg-6">
                                            <blockquote class="p-5 border-color-1 h-100">
                                                As a busy professional, I rely on my morning coffee to kickstart my day. The
                                                rich, smooth taste and heavenly aroma never fail to perk me up and get me
                                                ready to tackle whatever the day throws at me. It's like a little slice of
                                                heaven in a cup!<span>Jenna Smith</span>
                                            </blockquote>
                                        </div>
                                    </div>

                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                        incididunt
                                        ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat. Lorem ipsum dolor
                                        sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore
                                        et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation
                                        ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in
                                        reprehenderit in voluptate velit esse cillum dolore eu fugiat. Lorem ipsum dolor sit
                                        amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et
                                        dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                        laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor
                                        in reprehenderit in voluptate velit esse cillum dolore eu fugiat.</p>
                                </div>
                            </div>

                            <div class="post-meta"><span><i class="fa fa-user id-color"></i>By: <a href="#">Lynda
                                        Wu</a></span> <span><i class="fa fa-tag id-color"></i><a href="#">News</a>, <a
                                        href="#">Events</a></span> <span><i class="fa fa-comment id-color"></i><a
                                        href="#">10 Comments</a></span></div>

                            <div class="spacer-single"></div>

                            <div id="blog-comment">
                                <h3>Comments (5)</h3>

                                <div class="spacer-half"></div>

                                <ol>
                                    <li>
                                        <div class="avatar">
                                            <img src="client/03_images/ui/avatar.png" alt="" />
                                        </div>
                                        <div class="comment-info">
                                            <span class="c_name">John Smith</span>
                                            <span class="c_date">8 August 2018</span>
                                            <span class="c_reply"><a href="#">Reply</a></span>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="comment">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                            accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
                                            inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                                        <ol>
                                            <li>
                                                <div class="avatar">
                                                    <img src="client/03_images/ui/avatar.png" alt="" />
                                                </div>
                                                <div class="comment-info">
                                                    <span class="c_name">John Smith</span>
                                                    <span class="c_date">8 August 2018</span>
                                                    <span class="c_reply"><a href="#">Reply</a></span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="comment">Sed ut perspiciatis unde omnis iste natus error sit
                                                    voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque
                                                    ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                                                    dicta sunt explicabo.</div>
                                            </li>
                                        </ol>
                                    </li>

                                    <li>
                                        <div class="avatar">
                                            <img src="client/03_images/ui/avatar.png" alt="" />
                                        </div>
                                        <div class="comment-info">
                                            <span class="c_name">John Smith</span>
                                            <span class="c_date">8 August 2018</span>
                                            <span class="c_reply"><a href="#">Reply</a></span>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="comment">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                            accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
                                            inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                                        <ol>
                                            <li>
                                                <div class="avatar">
                                                    <img src="client/03_images/ui/avatar.png" alt="" />
                                                </div>
                                                <div class="comment-info">
                                                    <span class="c_name">John Smith</span>
                                                    <span class="c_date">8 August 2018</span>
                                                    <span class="c_reply"><a href="#">Reply</a></span>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="comment">Sed ut perspiciatis unde omnis iste natus error sit
                                                    voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque
                                                    ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae
                                                    dicta sunt explicabo.</div>
                                            </li>
                                        </ol>
                                    </li>

                                    <li>
                                        <div class="avatar">
                                            <img src="client/03_images/ui/avatar.png" alt="" />
                                        </div>
                                        <div class="comment-info">
                                            <span class="c_name">John Smith</span>
                                            <span class="c_date">8 August 2018</span>
                                            <span class="c_reply"><a href="#">Reply</a></span>

                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="comment">Sed ut perspiciatis unde omnis iste natus error sit voluptatem
                                            accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
                                            inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.</div>
                                    </li>
                                </ol>

                                <div class="spacer-single"></div>

                                <div id="comment-form-wrapper">
                                    <h4>Leave a Comment</h4>
                                    <div class="comment_form_holder">
                                        <form id="contact_form" name="form1" method="post" action="#">

                                            <label>Name</label>
                                            <input type="text" name="name" id="name" class="form-control" />

                                            <label>Email <span class="req">*</span></label>
                                            <input type="text" name="email" id="email" class="form-control" />
                                            <div id="error_email" class="error">Please check your email</div>

                                            <label>Message <span class="req">*</span></label>
                                            <textarea cols="10" rows="10" name="message" id="message" class="form-control"></textarea>
                                            <div id="error_message" class="error">Please check your message</div>
                                            <div id="mail_success" class="success">Thank you. Your message has been sent.
                                            </div>
                                            <div id="mail_failed" class="error">Error, email not sent</div>

                                            <p id="btnsubmit">
                                                <input type="submit" id="send" value="Send"
                                                    class="btn-custom" />
                                            </p>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>



    </div>
@endsection
