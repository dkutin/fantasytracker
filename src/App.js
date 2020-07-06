import React, { Component } from 'react';
import $ from 'jquery';
import './App.css';
import Header from './components/Header';
import Footer from './components/Footer';
import Featured from './components/Featured';

class App extends Component {

    constructor(props){
        super(props);
        this.state = {
            playerData: {}
        };
    }

    getPlayerData(){
        $.ajax({
            url:"/playerData.json",
            dataType:'json',
            cache: false,
            success: function(data){
                this.setState({data: data});
            }.bind(this),
            error: function(xhr, status, err){
                console.log(err);
                alert(err);
            }
        });
    }

    componentDidMount(){
        this.getPlayerData();
    }

    render() {
        return (
            <div className="App">
                <Header/>
                <Featured data={this.state.data}/>
                <Footer/>
            </div>
        );
    }
}

export default App;
