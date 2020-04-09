import React, {Component} from 'react';

export default class Combatant extends Component {
    render() {
        let health = this.props.health;
        if (this.props.health < 0) {
            health = 0;
        }
        const healthPercent = Math.floor(health / this.props.maxHealth * 100) + '%';

        return (
            <div>
                <div className="flex w-full text-2xl">
                    <div className="inline-flex w-1/2 "><h2 className="font-bold">
                        {this.props.name}
                    </h2>
                    </div>
                    <div className="inline-flex text-right w-1/2"><h2
                        className="text-right w-full">Lv. {this.props.level}</h2></div>
                </div>
                <div className="flex w-full mt-3">
                    <div className="shadow w-full bg-gray-700">
                        <div
                            className="bg-green-500 text-xs leading-none py-1 text-center text-white h-3"
                            style={{width: healthPercent}}/>
                    </div>
                </div>
            </div>
        );
    }
}
