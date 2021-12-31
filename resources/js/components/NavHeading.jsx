import React, { Component } from 'react';

export default class NavHeading extends Component {

    constructor(props, context) {
        super(props, context);
    }

    render() {
        return (
            <h1 className="flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">{this.props.name}</h1>
        );
    }
}
