import { __ } from '@wordpress/i18n';

export default function OpenHours( { days = [] } ) {

    const getDayAbbreviated = ( day ) => {
        switch ( day ) {
            case 0:
                return __( 'Sun', 'google-places-reviews' );
            case 1:
                return __( 'Mon', 'google-places-reviews' );
            case 2:
                return __( 'Tue', 'google-places-reviews' );
            case 3:
                return __( 'Wed', 'google-places-reviews' );
            case 4:
                return __( 'Thu', 'google-places-reviews' );
            case 5:
                return __( 'Fri', 'google-places-reviews' );
            case 6:
                return __( 'Sat', 'google-places-reviews' );
            default:
                return '';
        }
    };

    const isCurrentDay = ( day ) => {
        // getting day digit
        const today = new Date().getDay();

        return day === getDayAbbreviated( today );
    };

    return (
        <div className={'rbg-business-hours-wrap'}>
            {days.map( ( day, index ) => {

                const dayArray = day.split( ':' );
                const dayWordAbbreviated = dayArray[0].substring( 0, 3 );
                const hours = day.replace( /^\w+:/, '' ).trim();

                return (
                    <div key={index}
                         className={`rbg-business-hours rbg-business-hours__today-${isCurrentDay( dayWordAbbreviated )}`}>
                        <span className={'rbg-business-hours__day'}>{dayWordAbbreviated}: </span>
                        <span className={'rbg-business-hours__time'}>{hours}</span>
                    </div>
                );
            } )}
        </div>
    );

}
