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
                            console.debug("Game was updated", event.isLocal? "locally." : "by the other guy.");
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
                    <hr/>
                    <div className="columns">
                        <div className="column">
                            <Combatant combatant={this.state.data.combatantOne} color='warning' icon='code' />
                        </div>
                        <div className="column">
                            <Combatant combatant={this.state.data.combatantTwo} color='danger' icon='bug' />
                        </div>
                    </div>
                    <hr/>
                    <div className="columns">
                        <div className="column"/>
                        <div className="column is-three-fifths">
                            <Moves moves={this.state.data.combatantOne.moves} />
                        </div>
                        <div className="column"/>
                    </div>
                    <hr />
                    <div className="columns">
                        <div className="column">
                            {this.state.data.feedback.length > 0 &&
                            <Feedback feedback={this.state.data.feedback}/>
                            }
                        </div>
                    </div>
                </div>
            );
        } else {
            return '';
        }
    }
}
