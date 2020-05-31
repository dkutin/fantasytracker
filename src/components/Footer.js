import React, { Component } from 'react';

class Footer extends Component {
    render() {
        return (
            <footer>
                <div classname="row">
                    <hr/>
                    <div className="three-col">
                        <p> Made by Dmitry Kutin </p>
                    </div>
                    <div className="three-col">
                        <p> Check out my <a className="link" href="https://github.com/dkutin"> Github </a> </p>
                    </div>
                    <div className="three-col">
                        <p>
                            Green: Player performing well. <br/>
                            Yellow: Unsure of players performance. <br/>
                            Red: Player is under performing. <br/>
                        </p>
                    </div>
                </div>
            </footer>
        );
    }
}


export default Footer;
