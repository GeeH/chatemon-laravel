import React, {Component} from 'react';

export default class Feedback extends Component {
    render() {
        return (
            <div className="absolute p-5 text-center w-full bg-gray-700 text-white text-2xl"
                 style={{top: '20%'}}>

                {this.props.feedback.map((feedback, index) => {
                    return <p key={index} className="p-1" dangerouslySetInnerHTML={{__html: feedback}}/>
                })}

                <p className="pt-3 text-sm">Move played by a player from&nbsp;
                    <span className="font-bold">{this.props.move_country}</span>
                    &nbsp;ending in <span className="font-bold">{this.props.move_number}</span>
                </p>
            </div>
        );
    }
}
