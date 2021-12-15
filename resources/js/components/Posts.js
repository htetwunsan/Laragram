import React from "react";
import Post from "./Post";

class Posts extends React.Component {

    constructor(props) {
        super(props);
    }

    componentDidMount() {
        axios.get('/api/home').then(response => {
            this.setState(({
                users: response.data
            }));
        });
    }

    render() {
        if (this.state == null) {
            return <h1>Loading...</h1>
        }
        const users = this.state.users;
        const items = users.map(user => <Post key={user.id} user={user}/>);
        return (
            <div>{items}</div>
        );
    }
}

export default Posts;
