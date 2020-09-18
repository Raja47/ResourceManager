import React ,{ Component , Fragmnent} from 'react'
import {Button, Carousel ,Container ,Row,Col,Card} from 'react-bootstrap';
import { connect } from 'react-redux'

// import  searchResourcesReducer from "./../../../reducers/resources.js";
import { searchResourcesAction ,}  from "./../../../actions/resourceActions";


/* components link */

import Carouselslider from './partials/carousels/carousels.jsx';

/*import Formaction from '../signup/sendgridcode';*/
/* components link */


class Home extends Component{
    
    constructor(props) {
        super(props);
        this.state = {
            resources:[],
            
        };
        
    }
    componentDidMount() {
        this.props.dispatch(searchResourcesAction());
        
    }

	render () {
		return (
		   <div className="App">
		  
		    <Container className="MainAppFluid" fluid>
		       
		      <Carouselslider/><br/>
		      
		    </Container>
		    
		    </div>
		 );
	}
}

function mapStateToProps(state) {
    return {  
        resources: state.searchResourcesReducer, 
    }
}
export default connect(mapStateToProps)(Home)

// const mapStateToProps = (state) => ({
//   resources: searchResourcesReducer(state),
// })
