import React, {Component} from 'react';
import Combatant from "./Combatant";
import Moves from "./Moves";
import Feedback from "./Feedback";
import Client, {SyncClient} from "twilio-sync/lib/client";

export default class Combat extends Component {
    constructor(props) {
        super(props);
        this.state = {data: {}};
        this.handleDocumentLoad = this.handleDocumentLoad.bind(this);
    }

    componentDidMount() {
        // replace http request with getting initial state from sync
        // bind event handler to sync update event so it updates React's state
        fetch('/token')
            .then(response => {
                return response.json();
            })
            .then(json => {
                // json = tokenData in object
                let syncClient = new Client(json.token, {logLevel: 'info'});
                syncClient.on('connectionStateChanged', function (state, syncClient) {
                    if (state !== 'connected') {
                        console.log('Sync is not live (websocket connection ' + state);
                    } else {
                        console.log('Sync is connected');
                    }
                });

                syncClient.document('CHATEMON_FIGHT')
                    .then(syncDoc => {
                        this.handleDocumentLoad(syncDoc);
                        syncDoc.on('updated', event => {
                            console.debug("Game was updated", event.isLocal ? "locally." : "by the other guy.");
                            this.handleDocumentLoad(syncDoc);
                        });
                    });

            });
    }

    handleDocumentLoad(syncDoc) {
        const data = syncDoc.value;
        this.setState({data: data});
    }

    render() {
        if (typeof this.state.data.combatantOne == 'object') {
            return (

                <div>
                    <div className="w-screen h-screen flex">

                        <div className="w-1/2 flex items-end h-full">

                            <div className="w-full">
                                <div className="mb-5 w-full flex flex-row">
                                    <div
                                        className="bg-gray-100 p-4 my-5 w-full h-20 text-2xl leading-none shadow-inner">
                                        <div className="flex">
                                            <div className="flex mr-4">
                                                <img src="img/icon-message-chat-red.svg" alt="logo"/>
                                            </div>
                                            <div className="flex flex-col">
                                                <p>Send an SMS to: <span className="font-bold">+44 7723 501858</span>
                                                </p>
                                                <p>with the move you wish to play (<span
                                                    className="font-bold">A</span>, <span
                                                    className="font-bold">B</span> or <span
                                                    className="font-bold">C</span>)
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div className="bg-gray-100 p-4 my-5 w-2/3 h-20">
                                    <Combatant
                                        name={this.state.data.combatantOne.name}
                                        level={this.state.data.combatantOne.level}
                                        health={this.state.data.combatantOne.health}
                                        maxHealth={this.state.data.combatantOne.maxHealth}
                                    />
                                </div>
                            </div>
                        </div>

                        <div className="w-1/2 flex flex-col content-between items-end right">

                            <div className="mb-auto w-full content-between items-end flex flex-col">
                                <div className="bg-gray-100 p-4 my-5 w-2/3 h-20">
                                    <Combatant
                                        name={this.state.data.combatantTwo.name}
                                        level={this.state.data.combatantTwo.level}
                                        health={this.state.data.combatantTwo.health}
                                        maxHealth={this.state.data.combatantTwo.maxHealth}
                                    />
                                </div>
                            </div>

                            <Moves moves={this.state.data.combatantOne.moves} movePlayed={this.state.data.movePlayed}/>

                        </div>

                    </div>

                    {this.state.data.feedback.length > 0 &&
                    <Feedback feedback={this.state.data.feedback} move_country={this.state.data.fromCountry}
                              move_number={this.state.data.fromNumber} />
                    }

                </div>
            );
        } else {
            return '';
        }
    }
}
