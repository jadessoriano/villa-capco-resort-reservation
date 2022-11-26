<div class="ml-1 text-sm" x-data="{ open: false }">
  <label for="terms" class="font-medium">I have read and agree to the <span id="terms-label" class="ml-1 text-secondary-fg hover:text-primary-fg cursor-pointer" @click="open = !open">Terms and Condition</span></label>
  <div class="relative z-50" x-show="open" @click="open = false">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-50 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
        <div class="overflow-x-hidden overflow-y-auto fixed h-modal md:h-full top-4 left-0 right-0 md:inset-0 z-50 justify-center items-center">
            <div class="relative flex justify-center items-center mx-auto w-full max-w-2xl px-4 h-full">
                <!-- Modal content -->
                <div class="bg-white rounded-lg shadow relative dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-gray-900 capitalize text-xl lg:text-2xl font-semibold dark:text-white">
                          Terms and Conditions in Villa Capco Resort
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" @click="open = false">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>  
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-6 space-y-2 overflow-y-auto h-1/2-screen">
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	All swimmers must be in Proper Swimming Attire. NOT COTTON
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Reservation fee of (2,000) will be required to ensure the specified schedule of renter.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Party Host may come no more than 30 mins before start time to set-up
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Decorations are only allowed in the provided backdrop area.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	No balloons in the pool water, celling décor, confetti, party poppers, felt paper for decorations, double sided tape and Nails ARE NOT ALLOWED.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Day session is from 7am-5pm
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Night session is 7pm-5am NO EXTENSION ALLOWED UNLESS PERMITTED BY THE MANAGEMENT 10% will be charge per hour.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	20 person is included in the package: Excess of guest will be charged.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Regardless if swimmer or Non-swimmer guest.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Incase of which an additional fee excess of guest will be charge 200 per person. NO EXEMPTION FOR NON-SWIMMER GUEST.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	3rd yr. below free of charge.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Changes in the reservations or cancellations may still be entertained seven days (7) before the specified date. However, re-scheduling of reservations would be subjected to the availability of the private resort. Reservation is NON-REFUNDABLE after 7 days prior to the Event Date. For a week prior reservation refund, (1000) would be deducted on the initial down payment.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Full payment shall be upon arrival at the resort.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	For videoke rules; at 10pm (Our staff will ask to lower down videoke volume and ask to lower down voices due to respect to our neighbors and city ordinance.)
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Parents or Guardian are to supervise their children at all times.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	We reserve the right to refuse the use of pool to anyone who doesn’t conduct themselves in a safety manners.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	Guests must properly observe house rules. Fire arms and illegal drugs are strictly prohibited.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	It is understood that the management is not responsible for any accident, injuries and losses to any of the guests or their belongings during the tenure of the lease.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	The guest waves the right to claim damages against the owner/management.
                        </p>
                        <p class="text-gray-500 text-base leading-relaxed dark:text-gray-400">
                          •	It is our standard procedure to check items and equipment (30) before check out of guests, therefore, items and equipment found missing or damage will be charge in the Renter.
                        </p>
                    </div>
                    <!-- Modal footer -->
                    <div id="modal-terms-footer" class="flex justify-end items-center p-6 border-t border-gray-200 rounded-b dark:border-gray-600">
                      <x-button
                      ::disabled="!isChecked"
                      wire:loading.remove
                      wire:click="reserve">Confirm</x-button>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
