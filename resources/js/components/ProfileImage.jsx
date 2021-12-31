import React, { Component } from 'react';

export default class ProfileImage extends Component {

    constructor(props) {
        super(props);
    }

    render() {
        if (this.props.url) {
            return <img className="w-full h-full rounded-full" src={this.props.url} />
        }
        return <img className="w-full h-full rounded-full" src="/images/default-profile.jpeg" />;
    }
}
