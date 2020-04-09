import React, {Component} from 'react';

export default class Moves extends Component {
    render() {
        const buttons = this.props.moves.map((move, index) => {
            const bgColor = (index === this.props.movePlayed) ? 'bg-green-300' : 'bg-gray-100';
            console.log(bgColor);
            return (
                <div key={index} className={'flex w-full m-4 text-2xl justify-between rounded-full ' + bgColor}>
                    <div className="w-full p-4">
                        <h3 className="">{(index + 10).toString(36).toUpperCase()}: &nbsp;
                            <span className="font-bold">{move.name}</span></h3>
                    </div>
                    <div className="w-1/3 bg-black text-white p-4 rounded-full text-center">
                        {move.accuracy}/{move.damage}
                    </div>
                </div>
            );
        });

        return (
            <div className="w-2/3 mr-8">
                {buttons}
            </div>
        );
    }
}
