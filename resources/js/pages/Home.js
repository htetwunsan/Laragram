import React from "react";
import ReactDOM from "react-dom";
import Posts from "../components/Posts";

class Home extends React.Component {
    render() {
        return <Posts/>
    }
}

export default Home;

if (document.getElementById('react_home_container')) {
    const container = document.getElementById('react_home_container');
    ReactDOM.render(<Home/>, container);
}
