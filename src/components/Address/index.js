import { __ } from '@wordpress/i18n';
import MapPin from '../../images/map-pin.svg';

export default function Address( { displayAddress = [], mapsUrl } ) {

    const addressParts = displayAddress.split( ',' );

    return (
        <div className={'rbg-display-address-wrap'}>
            <img src={MapPin} alt={__( 'Business address', 'google-places-reviews' )}/>
            <address>
                {addressParts.map( ( addressPart, index ) => {

                    if ( 0 === index ) {
                        return (
                            <a key={index} href={mapsUrl}
                               target={'_blank'} dangerouslySetInnerHTML={{__html: addressPart}} />
                        )
                    }
                    return (
                        <span key={index} dangerouslySetInnerHTML={{__html: addressPart}} />
                    );

                } )}
            </address>
        </div>
    )
}

