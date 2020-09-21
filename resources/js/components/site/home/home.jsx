import React ,{ Component , Fragmnent} from 'react'
import {Button, Carousel ,Container ,Row,Col,Card} from 'react-bootstrap';
import { connect } from 'react-redux'
import { searchResourcesAction ,}  from "./../../../actions/resourceActions";
import Carouselslider from './partials/carousels/carousels.jsx';



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


    componentDidUpdate(prevProps) {
	  // Typical usage (don't forget to compare props):
	  if (this.props.resources !== prevProps.resources) {
	    this.setState({resources:this.props.resources});
	  }
	}

    // componentWillReceiveProps(nextProps) {
        
    //     if(nextProps.resources !== null && nextProps.resources !== null){
    //         if(nextProps.resources !== this.props.resources){
                 
    //             // var siteSelectdData = nextProps.sites.map(site => {
    //             //         let rObj = {}
    //             //        rObj['value'] = site.id;
    //             //        rObj['label'] = site.title;
    //             //        return rObj
    //             // });
    //             this.setState({resources:nextProps.resources});
    //         }
    //     }
        
    // }

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
        resources: state.searchResourcesReducer.searchedResources, 
    }
}

export default connect(mapStateToProps)(Home)

// const mapStateToProps = (state) => ({
//   resources: searchResourcesReducer(state),
// })
