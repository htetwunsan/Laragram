import React, { Component } from 'react';
import TopNavigation from "../TopNavigation";
import BackButton from "../BackButton";
import NavHeading from "../NavHeading";
import SearchResultUser from './SearchResultUser';
import SelectedUser from './SelectedUser';
import AddPeopleNextButton from "./AddPeopleNextButton";
import WithRouter from '../WithRouter';

class AddPeople extends Component {

    constructor() {
        super();

        this.Repo = {
            searchUsers: _.debounce(query => {
                axios.get('/api/explore/search', { params: { q: query } }).then(response => {
                    this.setState({
                        searchResultUsers: response.data
                    });
                });
            }, 200)
        };

        this.state = {
            query: "",
            users: [],
            searchResultUsers: []
        };

        this.inputRef = React.createRef();
    }

    handleSearch = e => {
        this.setState({ query: e.target.value }, () => {
            const { query } = this.state;
            if (!query) {
                this.setState({ searchResultUsers: [] });
                return;
            }
            this.Repo.searchUsers(query);
        });
    }

    handleClickSearchResultUser = (e, user) => {
        this.setState(({ users }) => {
            const exists = users.some(e => e.id == user.id);

            if (exists) {
                return {
                    users: users.filter(e => e.id != user.id),
                };
            }

            this.inputRef.current.focus();
            return {
                query: "",
                users: [...users, user],
                searchResultUsers: []
            };
        });
    }

    handleClickSelectedUser = (e, user) => {
        this.setState(({ users }) => ({ users: users.filter(e => e.id != user.id) }));
    }

    render() {
        return (
            <section id="section_main" className="flex-grow flex flex-col items-stretch overflow-y-auto no-scrollbar">
                <TopNavigation left={<BackButton handleClick={e => this.props.navigate(-1)} />}
                    center={<NavHeading name="Add People" />}
                    right={<AddPeopleNextButton users={this.state.users} />} />

                <main className="bg-white flex-grow flex flex-col items-stretch" role="main">
                    <div className="flex flex-col items-stretch border border-b border-triple219 overflow-x-hidden overflow-y-auto" style={{ maxHeight: '33vh' }}>
                        <div className="flex flex-row my-2">
                            <div className="flex flex-col items-stretch py-1 px-3">
                                <h4 className="text-base text-triple38 font-semibold leading-6">To:</h4>
                            </div>

                            <div className="flex flex-row items-center flex-wrap">
                                {
                                    this.state.users.map(
                                        user => <SelectedUser user={user} handleClick={this.handleClickSelectedUser} key={user.id} />
                                    )
                                }
                                <input className="text-sm text-triple38 placeholder-triple142 py-1 px-3 border-none focus:ring-0"
                                    style={{ lineHeight: '30px' }}
                                    ref={this.inputRef}
                                    onChange={this.handleSearch}
                                    autoComplete="off" placeholder="Search..." spellCheck="false" type="text" value={this.state.query} />
                            </div>
                        </div>
                    </div>

                    <div className="flex flex-col items-stretch">
                        {
                            this.state.searchResultUsers.map(
                                user => <SearchResultUser user={user} selected={this.state.users.some(e => e.id == user.id)}
                                    handleClick={this.handleClickSearchResultUser} key={user.id} />
                            )
                        }
                    </div>
                </main>
            </section>
        );
    }
}

export default WithRouter(AddPeople);
