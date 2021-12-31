import React from "react";
import $ from "jquery";

class Post extends React.Component {

    constructor(props) {
        super(props);
    }

    componentDidMount() {
        const $carousel = $(this.carousel);
        const $btnNext = $(this.btnNext);
        const $btnPrevious = $(this.btnPrevious);

        function toggleNext(valid) {
            valid ? $btnNext.removeClass('hidden') : $btnNext.addClass('hidden');
        }

        function togglePrevious(valid) {
            valid ? $btnPrevious.removeClass('hidden') : $btnPrevious.addClass('hidden');
        }

        $carousel.scroll(function () {
            togglePrevious($(this).scrollLeft() >= $(this).width() / 2);
            toggleNext($(this)[0].scrollWidth - ($(this)[0].scrollLeft + $(this)[0].clientWidth) >= $(this).width() / 2);
        });

        $btnNext.click(function () {
            $carousel.scrollLeft($carousel.scrollLeft() + $carousel.width());
        });

        $btnPrevious.click(function () {
            $carousel.scrollLeft($carousel.scrollLeft() - $carousel.width());
        });

        $carousel.trigger('scroll');
    }

    render() {
        return (
            <article className="flex flex-col items-stretch mb-4" tabIndex="-1" role="presentation">
                <div className="flex flex-col items-stretch border border-triple239">
                    <header className="flex flex-auto flex-row items-center pl-4 py-3.5 pr-1">
                        <a className="block w-8 h-8 rounded-full"
                            href={this.props.user.username}>
                            <img className="w-full h-full rounded-full" src={`storage/${this.props.user.profile_image}`}
                                alt={`{ ${this.props.user.username} }}\`s profile image`} />
                        </a>
                        <a className="block flex-grow ml-4 text-sm text-triple38 font-semibold leading-18px overflow-ellipsis"
                            href={this.props.user.username}>
                            {this.props.user.username}
                        </a>
                        <div className="flex items-center justify-center pr-2">
                            <button type="button">
                                <svg aria-label="More options" color="#262626" fill="#262626"
                                    height="24" role="img" viewBox="0 0 24 24" width="24">
                                    <circle cx="12" cy="12" r="1.5"></circle>
                                    <circle cx="6.5" cy="12" r="1.5"></circle>
                                    <circle cx="17.5" cy="12" r="1.5"></circle>
                                </svg>
                            </button>
                        </div>
                    </header>
                </div>
                <div className="flex flex-col items-stretch bg-black relative">
                    <button ref={ref => this.btnPrevious = ref}
                        className="absolute left-0 top-1/2 transform -translate-y-1/2 px-2 py-4 z-10"
                        type="button"
                        tabIndex="-1"
                    >
                        <span className="block grey-previous-chevron"></span>
                    </button>
                    <button
                        ref={ref => this.btnNext = ref}
                        className="absolute right-0 top-1/2 transform -translate-y-1/2 px-2 py-4 z-10"
                        type="button"
                        tabIndex="-1"
                    >
                        <span className="block grey-next-chevron"></span>
                    </button>
                    <div ref={ref => this.carousel = ref}
                        className="flex flex-row items-center overflow-x-auto overflow-y-hidden no-scrollbar"
                        style={{ scrollBehavior: "smooth", overflowScrolling: "touch", scrollSnapType: "x mandatory" }}
                    >
                        {
                            [1, 2, 3, 4, 5, 6, 7, 8, 9, 10].map(element => {
                                return (
                                    <div key={element} className={"w-full flex-none"}
                                        style={{ scrollSnapAlign: "start" }}>
                                        <img className="w-full h-full object-cover" src="/images/stars.jpeg"
                                            alt="image" />
                                    </div>
                                )
                            })
                        }
                    </div>
                </div>
                <div className="flex flex-col items-stretch px-4 py-1.5">
                    <div className="flex flex-row justify-between items-center relative">
                        <div
                            className="absolute top-0 left-1/2 transform -translate-x-1/2 z-10 flex flex-row -mt-1">
                            <span className="block text-triple38 text-3xl leading-none -mx-0.5">
                                •
                            </span>
                        </div>
                        <div className="flex flex-row justify-evenly items-center gap-4">
                            <div className="py-2">
                                <button id="btn_unlike" className="block" type="button">
                                    <svg aria-label="Unlike" className="text-error fill-current w-6 h-6"
                                        role="img" viewBox="0 0 48 48">
                                        <path
                                            d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                                        </path>
                                    </svg>
                                </button>
                                <button id="btn_like" className="hidden" type="button">
                                    <svg aria-label="Like" className="text-triple38 fill-current w-6 h-6"
                                        role="img" viewBox="0 0 48 48">
                                        <path
                                            d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <button id="btn_comment" className="block py-2" type="button">
                                <svg aria-label="Comment" className="text-triple38 fill-current w-6 h-6"
                                    role="img" viewBox="0 0 48 48">
                                    <path clipRule="evenodd"
                                        d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z"
                                        fillRule="evenodd">

                                    </path>
                                </svg>
                            </button>
                            <button id="btn_share" className="block py-2" type="button">
                                <svg aria-label="Share Post" className="text-triple38 fill-current w-6 h-6"
                                    role="img" viewBox="0 0 48 48">
                                    <path
                                        d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">

                                    </path>
                                </svg>
                            </button>
                        </div>
                        <div className="py-2">
                            <button id="btn_save" className="block" type="button">
                                <svg aria-label="Save" className="text-triple38 fill-current w-6 h-6"
                                    role="img" viewBox="0 0 48 48">
                                    <path
                                        d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z">

                                    </path>
                                </svg>
                            </button>
                            <button id="btn_unsave" className="hidden" type="button">
                                <svg aria-label="Remove" className="text-triple38 fill-current w-6 h-6"
                                    role="img" viewBox="0 0 48 48">
                                    <path
                                        d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z">

                                    </path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </article>
        );
    }
}

export default Post;
