import React from "react";
import ReactDOM from "react-dom";
import { BrowserRouter, Routes, Route, Navigate } from "react-router-dom";
import Chat from "./Chat";
import Chats from "./Chats";
import New from "./New";
import { AppContext } from "./AppContext";
import ChatDetails from "./ChatDetails";
import AddPeople from "./AddPeople";

class App extends React.Component {

    constructor() {
        super();
        this.state = {
            authUser: null
        };
    }

    componentDidMount() {
        axios.get('/api/auth/user').then(response => {
            this.setState({
                authUser: response.data
            });
        });
    }


    render() {
        if (!this.state.authUser) {
            return <></>;
        }
        // TODO - before upload change Navigate to Chats
        return <>
            <AppContext.Provider value={this.state}>
                <Routes>
                    <Route path="/direct">
                        <Route index element={<Navigate to="inbox" />} />
                        <Route path="inbox" element={<Chats />} />
                        <Route path="new" element={<New />} />
                        <Route path="r/:roomId" element={<Chat />} />
                        <Route path="r/:roomId/details" element={<ChatDetails />} />
                        <Route path="r/:roomId/details/add" element={<AddPeople />} />
                    </Route>
                </Routes>
            </AppContext.Provider>
        </>;
    }
}


export default App;

const element = document.getElementById('chat_app');

if (element) {
    ReactDOM.render(
        <BrowserRouter>
            <App />
        </BrowserRouter>,
        element);
}
