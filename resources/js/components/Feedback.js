import React, {Component} from 'react';

export default class Feedback extends Component {
    render() {
        return (
            <div>
                <div className="notification has-text-centered is-size-6">
                    Move played by number from <strong>{this.props.move_country}</strong> ending
                    in <strong>{this.props.move_number}</strong>
                </div>
                <div className="notification has-text-centered is-size-1">
                    {this.props.feedback.map((feedback, index) => {
                        return <h3 key={index} dangerouslySetInnerHTML={{__html: feedback}}/>;
                    })}
                </div>
            </div>
        );
    }
}
