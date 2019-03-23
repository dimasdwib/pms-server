<?php

use Illuminate\Database\Seeder;
use App\Models\Reservation\Reservation;
use App\Models\Reservation\ReservationRoom;
use App\Models\Reservation\ReservationGuest;

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
            'date_arrival' => '2019-03-23',
            'date_departure' => '2019-03-25',
            'rooms' => [
                [
                    'id' => 1,
                    'guests' => [
                        ['id' => 1]
                    ]
                ]
            ]
        ];
        $reservation = new Reservation;
        $reservation->id_booker = 1; 
        $reservation->status = 'definite';
        $reservation->note = 'reservation seeder test';
        $reservation->adult = 1;
        $reservation->child = 0;
        $reservation->save();
        $reservation_rooms = [];
        foreach($input['reservation']['rooms'] as $room) {
            $reservation_room = new ReservationRoom([
                'id_room' => $room['id'],
                'id_reservation' => $reservation->id_reservation
            ]);
            $reservation_room->save();
            foreach($room['guests'] as $guest) {
                $room_guest = new ReservationGuest([
                    'id_guest' => $guest['id'],
                    'id_reservation_room' => $reservation_room->id_reservation_room,
                    'date_arrival' => $input['reservation']['date_arrival'],
                    'date_departure' => $input['reservation']['date_departure'],
                ]);
                $room_guest->save();
            }
        }

        $input['reservation'] = [
            'date_arrival' => '2019-03-24',
            'date_departure' => '2019-03-25',
            'rooms' => [
                [
                    'id' => 2,
                    'guests' => [
                        ['id' => 2]
                    ]
                ],
                [
                    'id' => 3,
                    'guests' => [
                        ['id' => 2]
                    ]
                ]
            ]
        ];
        $reservation = new Reservation;
        $reservation->id_booker = 2; 
        $reservation->status = 'tentative';
        $reservation->note = 'reservation seeder test 2';
        $reservation->adult = 2;
        $reservation->child = 0;
        $reservation->save();
        $reservation_rooms = [];
        foreach($input['reservation']['rooms'] as $room) {
            $reservation_room = new ReservationRoom([
                'id_room' => $room['id'],
                'id_reservation' => $reservation->id_reservation
            ]);
            $reservation_room->save();
            foreach($room['guests'] as $guest) {
                $room_guest = new ReservationGuest([
                    'id_guest' => $guest['id'],
                    'id_reservation_room' => $reservation_room->id_reservation_room,
                    'date_arrival' => $input['reservation']['date_arrival'],
                    'date_departure' => $input['reservation']['date_departure'],
                ]);
                $room_guest->save();
            }
        }

        $input['reservation'] = [
            'date_arrival' => '2019-03-24',
            'date_departure' => '2019-03-25',
            'rooms' => [
                [
                    'id' => 4,
                    'guests' => [
                        ['id' => 3]
                    ]
                ],
                [
                    'id' => 5,
                    'guests' => [
                        ['id' => 3]
                    ]
                ]
            ]
        ];
        $reservation = new Reservation;
        $reservation->id_booker = 3; 
        $reservation->status = 'tentative';
        $reservation->note = 'reservation seeder test 2';
        $reservation->adult = 2;
        $reservation->child = 0;
        $reservation->save();
        $reservation_rooms = [];
        foreach($input['reservation']['rooms'] as $room) {
            $reservation_room = new ReservationRoom([
                'id_room' => $room['id'],
                'id_reservation' => $reservation->id_reservation
            ]);
            $reservation_room->save();
            foreach($room['guests'] as $guest) {
                $room_guest = new ReservationGuest([
                    'id_guest' => $guest['id'],
                    'id_reservation_room' => $reservation_room->id_reservation_room,
                    'date_arrival' => $input['reservation']['date_arrival'],
                    'date_departure' => $input['reservation']['date_departure'],
                ]);
                $room_guest->save();
            }
        }

    }
}
