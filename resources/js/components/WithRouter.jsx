import { useNavigate, useParams } from "react-router-dom"

const WithRouter = WrappedComponent => props => {
    const params = useParams();
    const navigate = useNavigate();

    return (
        <WrappedComponent {...props} params={params} navigate={navigate} />
    );
}

export default WithRouter;
