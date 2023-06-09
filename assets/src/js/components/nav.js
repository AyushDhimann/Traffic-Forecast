import { h } from 'preact'
import { Link } from 'preact-router/match'
import { __ } from '@wordpress/i18n'

export default function Nav () {
  // do not show navigation if user can not access settings anyway
  if (!window.Traffic_Forecast.showSettings) {
    return null
  }

  return (
    <div className='two nav'>
      <ul className='subsubsub'>
        <li><Link href='/' exact activeClassName='current'>{__('Stats', 'Traffic-Forecast')}</Link> | </li>
        <li><Link href='/settings' activeClassName='current'>{__('Settings', 'Traffic-Forecast')}</Link></li>
      </ul>
    </div>
  )
}
