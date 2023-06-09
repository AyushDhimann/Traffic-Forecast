import { h, Component } from 'preact'
import api from './../util/api.js'
import { __ } from '@wordpress/i18n'

export default class ButtonReset extends Component {
  constructor (props) {
    super(props)

    this.state = {
      saving: false,
      buttonText: __('Reset Statistics', 'Traffic-Forecast')
    }

    this.onClick = this.onClick.bind(this)
  }

  onClick (evt) {
    if (window.confirm(__('Are you sure you want to reset all of your statistics? This can not be undone.', 'Traffic-Forecast')) !== true) {
      return
    }

    evt.preventDefault()

    this.setState({
      saving: true,
      buttonText: __('Resetting - please wait', 'Traffic-Forecast')
    })

    api.request('/reset', {
      method: 'POST',
      body: {}
    }).then(() => {
      this.setState({
        buttonText: __('Done!', 'Traffic-Forecast') + ' ' + __('Page will reload in a few seconds.', 'Traffic-Forecast')
      })

      // reload page after 4 seconds
      window.setTimeout(() => {
        window.location.reload()
      }, 4000)
    }).finally(() => {
      window.setTimeout(() => {
        this.setState({
          saving: false,
          buttonText: __('Reset Statistics', 'Traffic-Forecast')
        })
      }, 4000)
    })
  }

  render (props, state) {
    const { saving, buttonText } = state
    return (
      <button
        type='button' className='button button-primary' onClick={this.onClick}
        disabled={saving}
      >{buttonText}
      </button>
    )
  }
}
