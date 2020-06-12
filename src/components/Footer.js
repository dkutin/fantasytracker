import React, { Component } from 'react';

class Footer extends Component {
    render() {
        return (
            <footer>
                <div id="footer">
                    <hr/>
                    <div className="two-col">
                        <p> Made by Dmitry Kutin </p>
                    </div>
                    <div className="two-col">
                        <p> Check out my <a className="link" href="https://github.com/dkutin"> Github </a> </p>
                    </div>
                </div>
            </footer>
        );
    }
}


export default Footer;
