<?php

use Illuminate\Database\Seeder;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\ReservationRoom;
use App\Models\Reservation\ReservationGuest;
use App\Models\Rate\Rate;
use App\Models\Transaction\RoomCharge;
use App\Models\Transaction\Bill;
use App\Models\Transaction\ReservationBill;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $input['reservation'] = [
            'booker' => ['id_guest' => 1],
            'status' => 'definite',
            'note' => 'Reservation note 0001',
            'date_arrival' => '2019-03-23',
            'date_departure' => '2019-03-25',
            'rooms' => [
                [
                    'id_room' => 1,
                    'guests' => [
                        ['id_guest' => 1]
                    ],
                    'rate' => [
                        'id_rate' => 1,
                    ],
                ]
            ]
        ];
        $this->makeReservation($input);

        $input['reservation'] = [
            'booker' => ['id_guest' => 2],
            'status' => 'tentative',
            'note' => 'Reservation note 22',
            'date_arrival' => '2019-03-24',
            'date_departure' => '2019-03-28',
            'rooms' => [
                [
                    'id_room' => 2,
                    'guests' => [
                        ['id_guest' => 2]
                    ],
                    'rate' => [
                        'id_rate' => 2,
                    ],
                ],
                [
                    'id_room' => 3,
                    'guests' => [
                        ['id_guest' => 2]
                    ],
                    'rate' => [
                        'id_rate' => 2,
                    ],
                ]
            ]
        ];
        $this->makeReservation($input);

        $input['reservation'] = [
            'booker' => ['id_guest' => 3],
            'status' => 'definite',
            'note' => 'Reservation note',
            'date_arrival' => '2019-03-24',
            'date_departure' => '2019-03-25',
            'rooms' => [
                [
                    'id_room' => 4,
                    'guests' => [
                        ['id_guest' => 3]
                    ],
                    'rate' => [
                        'id_rate' => 1,
                    ],
                ],
                [
                    'id_room' => 5,
                    'guests' => [
                        ['id_guest' => 3]
                    ],
                    'rate' => [
                        'id_rate' => 1,
                    ],
                ]
            ]
        ];
        $this->makeReservation($input);


        $input['reservation'] = [
            'booker' => ['id_guest' => 4],
            'status' => 'definite',
            'note' => 'Reservation note',
            'date_arrival' => '2019-03-24',
            'date_departure' => '2019-03-25',
            'rooms' => [
                [
                    'id_room' => 4,
                    'guests' => [
                        ['id_guest' => 3]
                    ],
                    'rate' => [
                        'id_rate' => 1,
                    ],
                ],
                [
                    'id_room' => 5,
                    'guests' => [
                        ['id_guest' => 3]
                    ],
                    'rate' => [
                        'id_rate' => 1,
                    ],
                ]
            ]
        ];
        $this->makeReservation($input);
    }

    private function makeReservation($input) {
        $reservation = new Reservation;
        $reservation->id_booker = $input['reservation']['booker']['id_guest']; 
        $reservation->status = $input['reservation']['status'];
        $reservation->note = $input['reservation']['note'];
        $reservation->adult = 2;
        $reservation->child = 0;
        $reservation->save();
        $reservation_rooms = [];
        foreach($input['reservation']['rooms'] as $room) {
            $reservation_room = new ReservationRoom([
                'id_room' => $room['id_room'],
                'id_reservation' => $reservation->id_reservation
            ]);
            $reservation_room->save();
            foreach($room['guests'] as $guest) {
                $room_guest = new ReservationGuest([
                    'id_guest' => $guest['id_guest'],
                    'id_reservation_room' => $reservation_room->id_reservation_room,
                    'date_arrival' => $input['reservation']['date_arrival'],
                    'date_departure' => $input['reservation']['date_departure'],
                ]);
                $room_guest->save();
            }

            // Insert room rate
            $room_rate = $room['rate'];
            $rate = Rate::where('id_rate', $room_rate['id_rate'])
                    ->forReservation($input['reservation']['date_arrival'], $input['reservation']['date_departure'])
                    ->first();
            foreach($rate['charges'] as $charge) {
                RoomCharge::create([
                    'id_reservation_room_guest' => $room_guest->id_reservation_room_guest,
                    'id_rate' => $rate['id_rate'],
                    'amount_nett' => $charge['amount_nett'],
                    'date' => $charge['date'],
                ]);
            }
        }

        // create bill
        $bill = new Bill;
        $bill->id_guest = $reservation->id_booker;
        $bill->save();

        $reservation_bill = new ReservationBill;
        $reservation_bill->id_reservation = $reservation->id_reservation;
        $reservation_bill->id_bill = $bill->id_bill;
        $reservation_bill->status = 'open';
        $reservation_bill->save();
    }
}
