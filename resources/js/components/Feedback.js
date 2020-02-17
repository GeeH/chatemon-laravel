import React, {Component} from 'react';

export default class Feedback extends Component {
    render() {
        return (
            <div className="notification has-text-centered is-size-1">
                {this.props.feedback.map((feedback, index) => {
                    return <h3 key={index} dangerouslySetInnerHTML={{__html: feedback}} />;
                })}
            </div>
        );
    }
}
