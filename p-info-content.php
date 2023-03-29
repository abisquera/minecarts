
<!-- User Information-->
<div class="card user-info-card mb-3">
    <div class="card-body d-flex align-items-center">
        <div class="user-info">
        <div class="d-flex align-items-center">
            <h5 class="mb-1" id="name"></h5>
        </div>
        <p class="mb-0" id="address"></p>
        </div>
    </div>
</div>
<a class="btn btn-primary mb-3 d-none" id="linkParkingLots">Edit Parking Lots</a>
<a class="btn btn-primary mb-3 " id="linkParkingRates">Edit Parking Rates</a>
<a class="btn btn-primary mb-3 " id="linkParkingAccounts">Add Parking Gate Account</a>
<a class="btn btn-primary mb-3" id="linkParkingBack">Back</a>
<!-- User Meta Data-->
<div class="card user-data-card">
    <div class="card-body " >
        <div class="row">
            <div class="col-6">
                <div class="single-counter-wrap text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" class="text-info"><path fill="currentColor" d="M4 3h16a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm1 2v14h14V5H5zm4 2h3.5a3.5 3.5 0 0 1 0 7H11v3H9V7zm2 2v3h1.5a1.5 1.5 0 0 0 0-3H11z"></path></svg>
                <h3 class="mb-1 text-info"><span id="lotSlot">100</span></h3>
                <p class="mb-0">Total Lots</p>
                </div>
            </div>
            <div class="col-6">
                <div class="single-counter-wrap text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-warning" width="32" height="32" viewBox="0 0 2048 2048"><path fill="currentColor" d="M29 1075q-29 64-29 133v72q0 38 10 73t30 65t48 54t62 40q15 35 39 63t55 47t66 31t74 11q69 0 128-34t94-94h708q35 60 94 94t128 34q69 0 128-34t94-94h162q27 0 50-10t40-27t28-41t10-50v-256q0-79-30-149t-82-122t-122-83t-150-30h-37l-328-328q-27-27-62-41t-74-15H256v128h29L29 1075zm1507 461q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10zM896 512h267q26 0 45 19l237 237H896V512zM768 768H309l99-219q8-17 24-27t35-10h301v256zm-384 768q-27 0-50-10t-40-27t-28-41t-10-50q0-27 10-50t27-40t41-28t50-10q27 0 50 10t40 27t28 41t10 50q0 27-10 50t-27 40t-41 28t-50 10z"></path></svg>
                <h3 class="mb-1 text-warning"><span class="counter" id="lotSlotReserved">0</span></h3>
                <p class="mb-0">Total Parked</p>
                </div>
            </div>
        </div>
    </div>
    <hr>
    <div class="card-body " >
        <table class="data-table w-100" id="dataTables">
            <thead>
            <tr>
                <th>#</th>
                <th>Plate Num</th>
                <th>Start - End</th>
                <th>Reservation</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody id="p-lots-data">
            </tbody>
        </table>
    </div>
</div>
<script>


</script>