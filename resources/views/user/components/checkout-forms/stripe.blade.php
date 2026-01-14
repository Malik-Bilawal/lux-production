<div class="p-6">
    <h3 class="font-semibold text-lg mb-4">Stripe</h3>
    <p class="text-gray-600 mb-3">
        Enter your card details to pay securely with Stripe.
    </p>
    <div>
        <label class="block mb-2">Card Number</label>
        <input type="text" name="stripe_card_number" class="w-full border rounded-md p-2" placeholder="4242 4242 4242 4242" required>
    </div>
    <div class="mt-3 flex gap-3">
        <div class="flex-1">
            <label class="block mb-2">Expiry Date</label>
            <input type="text" name="stripe_expiry" class="w-full border rounded-md p-2" placeholder="MM/YY" required>
        </div>
        <div class="flex-1">
            <label class="block mb-2">CVC</label>
            <input type="text" name="stripe_cvc" class="w-full border rounded-md p-2" placeholder="123" required>
        </div>
    </div>
</div>
