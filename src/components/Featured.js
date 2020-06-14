import React, {Component} from "react";
import Stats from './Stats';

class Featured extends Component {
    render() {
        var count = 0;
        if (this.props.data) {
            var players = this.props.data.players.map(function(player){
                count++;
                var info = player.info;
                var analysis = player.analysis;
                var stats = player.stats;
                var highlight = '';

                // Check that all values are set.
                if (typeof analysis[1] == 'undefined') analysis[1] = 0.00;
                if (typeof analysis[2] == 'undefined') analysis[2] = 0.00;
                if (typeof analysis[4] == 'undefined') analysis[4] = 0.00;
                
                // TODO: This is going to need rework as to how we suggest players
                if (analysis[1] >= analysis[2]) {
                    highlight = 'green';
                } else if (analysis[4] >= analysis[1] &&
                    analysis[4] >= analysis[2]) {
                    highlight = 'red';
                } else {
                    highlight = 'yellow';
                }
                return <div key={info.player_id} className="two-col player-item">
                    <div className="player-feature"> 
                        <img className="player-image" id={highlight} alt={info.full_name} src={info.image}/> <br/>
                        <span className="player-name"> {count}. {info.full_name}</span>
                        <div className="player-analysis">
                            <span className="analysis">
                                <strong> One Week </strong>: {analysis[1]}
                            </span><br/>
                            <span className="analysis">
                                <strong> Two Weeks </strong>: {analysis[2]}
                            </span><br/>
                            <span className="analysis">
                                <strong> One Month </strong>: {analysis[4]}
                            </span><br/>
                        </div>
                    </div>
                    <Stats key={info.player_id} data={stats}/>
                </div>
            })
        }
        return (
            <section>
            <div className="featured-content">
                <div className="row">
                    <div>
                        <h2 className="section-title"> All Players </h2>
                        {players}
                    </div>
                </div>
            </div>
            </section>
        );
    }
}

export default Featured;
