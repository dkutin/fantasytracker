import React, {Component} from "react";
import Item from './Item';

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
                return (
                    // TODO: Have only key, player, stat fields 
                        <Item key={info.player_id} highlight={highlight} stats={stats} analysis={analysis} count={count} info={info}/>
                    );
            })
        }
        return (
            <section>
            <div className="featured-content">
                <div className="row">
                <h2> All Players </h2>
                    <div className="players">
                        {players}
                    </div>
                </div>
            </div>
            </section>
        );
    }
}

export default Featured;
