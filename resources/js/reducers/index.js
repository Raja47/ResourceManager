  
import { combineReducers } from 'redux'

import resourceReducer from './resources.js'
import loginReducer from './auth.js'
import { loadingBarReducer } from 'react-redux-loading-bar'


export default combineReducers({
 
  resourceReducer,
  loginReducer,
  loadingBar: loadingBarReducer,
})
