import React from 'react'
import { render } from 'react-dom'
import { createStore, applyMiddleware } from 'redux'
import { Provider } from 'react-redux'
import { createLogger } from 'redux-logger'
import thunk from 'redux-thunk'
import reducer from './../reducers'
// import { getAllProducts } from './actions'
import App from './App.jsx'

	const middleware = [ thunk ];
	if (process.env.NODE_ENV !== 'production') {
	  middleware.push(createLogger());
	}

	const store = createStore(
		reducer, 
		/* preloadedState, */
		
		window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__() && applyMiddleware(...middleware) ,

	);

// store.dispatch(getAllProducts())

render(
  <Provider store={store}>
    <App />
  </Provider>,
  document.getElementById('app')
)