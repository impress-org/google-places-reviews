import { __ } from '@wordpress/i18n';
import MapPin from '../../images/map-pin.svg';

export default function Address( { displayAddress = [], alias } ) {

    const addressParts = displayAddress.split( ',' );

    return (
        <div className={'rby-display-address-wrap'}>
            <img src={MapPin} alt={__( 'Business address', 'yelp-widget-pro' )}/>
            <address>
                {addressParts.map( ( addressPart, index ) => {

                    if ( 0 === index ) {
                        return (
                            <a key={index} href={`https://www.yelp.com/map/${alias}`}
                               target={'_blank'}><span key={index}>{addressPart}</span></a>
                        )
                    }
                    return (
                        <span key={index}>{addressPart}</span>
                    );

                } )}
            </address>
        </div>
    )
}

