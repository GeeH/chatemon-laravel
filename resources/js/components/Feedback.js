import React, {Component} from 'react';

export default class Feedback extends Component {
    render() {
        return (
            <div className="notification has-text-centered">
                {this.props.feedback.map((feedback, index) => {
                    return <h3 key={index}>{feedback}</h3>;
                })}
            </div>
        );
    }
}
