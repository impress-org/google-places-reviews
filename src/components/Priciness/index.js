export default function Priciness( { priceLevel } ) {

    const outputPriceToDollars = ( priceLevel ) => {
        switch ( priceLevel ) {
            case 1:
                return '$';
            case 2:
                return '$$';
            case 3:
                return '$$$';
            case 4:
                return '$$$$';
            default:
                return 'N/A';
        }
    };


    return (
        <div className={`rbg-business-price`}>
            {outputPriceToDollars( priceLevel )}
        </div>
    );
}
