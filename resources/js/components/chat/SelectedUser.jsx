import React, { Component } from 'react';

export default class SelectedUser extends Component {

    constructor() {
        super();
        this.state = {
            active: false
        };
    }

    handleClick = e => {
        this.setState(({ active }) => ({ active: !active }));
    }

    render() {
        const { user, handleClick } = this.props;
        if (!user) return <></>;
        if (this.state.active) {
            return (
                <div className="bg-fb_blue flex flex-col items-stretch m-1 px-3 rounded" style={{ height: '35px' }} role="button"
                    onClick={e => handleClick(e, user)}>
                    <div className="flex-grow flex items-center justify-center">
                        <span className="block text-sm text-white text-center leading-18px">{user.username}</span>
                        <div className="ml-2">
                            <svg aria-label="Delete Item" color="#ffffff" fill="#ffffff" height="12" role="img" viewBox="0 0 24 24" width="12">
                                <polyline fill="none" points="20.643 3.357 12 12 3.353 20.647" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="3"></polyline>
                                <line fill="none" stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="3" x1="20.649" x2="3.354" y1="20.649" y2="3.354">

                                </line>
                            </svg>
                        </div>
                    </div>
                </div>
            );
        }
        return (
            <div className="bg-soft_fb_blue flex flex-col items-stretch m-1 px-3 rounded" style={{ height: '35px' }} role="button"
                onClick={this.handleClick}>
                <div className="flex-grow flex items-center justify-center">
                    <span className="block text-sm text-fb_blue text-center leading-18px">{user.username}</span>
                </div>
            </div>
        );
    }
}
