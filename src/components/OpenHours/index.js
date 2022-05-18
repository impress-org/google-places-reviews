import { __ } from "@wordpress/i18n";

export default function OpenHours( { hours = [] } ) {

    const getDay = ( day ) => {
        switch ( day ) {
            case 0:
                return __( 'Mon', 'google-places-reviews' );
            case 1:
                return __( 'Tue', 'google-places-reviews' );
            case 2:
                return __( 'Wed', 'google-places-reviews' );
            case 3:
                return __( 'Thu', 'google-places-reviews' );
            case 4:
                return __( 'Fri', 'google-places-reviews' );
            case 5:
                return __( 'Sat', 'google-places-reviews' );
            case 6:
                return __( 'Sun', 'google-places-reviews' );
            default:
                return '';
        }
    };

    const convertMilitaryTime = ( time ) => {

        time = time.replace(/(.{2})$/,':$1');
        time = time.split(':');

        const hours = Number(time[0]);
        const minutes = Number(time[1]);

        let timeValue;

        if (hours > 0 && hours <= 12) {
            timeValue= "" + hours;
        } else if (hours > 12) {
            timeValue= "" + (hours - 12);
        } else if (hours === 0) {
            timeValue= "12";
        }

        timeValue += (minutes < 10) ? ":0" + minutes : ":" + minutes;  // get minutes
        timeValue += (hours >= 12) ? " PM" : " AM";  // get AM/PM

        return timeValue;

    }

    const isCurrentDay = ( day ) => {
        const today = new Date().getDay();
        return day + 1 === today;
    }


    return (
        <div className={'rbg-business-hours-wrap'}>
            {hours.map( ( hour, index ) => {
                return (
                    <div key={index} className={`rbg-business-hours rbg-business-hours__today-${isCurrentDay(hour.open.day)}`}>
                        <span className={'rbg-business-hours__day'}>{getDay( hour.open.day )}</span>
                        <span className={'rbg-business-hours__time'}>{convertMilitaryTime( hour.open.time )} - {convertMilitaryTime( hour.open.time )}</span>
                    </div>
                )
            } )}
        </div>
    );

}
