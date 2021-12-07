<?php

namespace Modules\Dashboard\Model;

use Modules\Appointment\Model\Appointment;
use Modules\Base\Model\BaseModel;
use Modules\Base\Model\Status;
use Modules\Course\Model\Course;
use Modules\Member\Model\Member;
use Modules\Order\Model\Order;
use Modules\Service\Model\Service;

class Dashboard extends BaseModel{

    /**
     * @return mixed
     */
    public static function getCountData(){
        $count_data = [
            'client'  => Member::query()->where('status', Status::STATUS_ACTIVE)->count(),
            'service' => Service::query()->where('status', Status::STATUS_ACTIVE)->count(),
            'course'  => Course::query()->where('status', Status::STATUS_ACTIVE)->count(),
            'order'   => Order::query()->count()
        ];

        return json_decode(json_encode($count_data), FALSE);
    }

    /**
     * @return mixed
     */
    public static function getAppointmentData(){
        $appointments             = Appointment::query()->get();
        $progressing_appointments = Appointment::query()
                                               ->where('status', Appointment::PROGRESSING_STATUS)
                                               ->orderBy('updated_at', 'desc')
                                               ->get();
        $abort_appointments       = Appointment::query()->where('status', Appointment::ABORT_STATUS)->get();
        $completed_appointments   = Appointment::query()->where('status', Appointment::COMPLETED_STATUS)->get();
        $waiting_appointments     = Appointment::query()->where('status', Appointment::WAITING_STATUS)->get();

        $appointment_data = [
            'all'         => $appointments,
            'progressing' => $progressing_appointments,
            'waiting'     => $waiting_appointments,
            'completed'   => $completed_appointments,
            'abort'       => $abort_appointments,
        ];

        return $appointment_data;
    }

    /**
     * Get Data in An Year
     *
     * @param null $year
     * @return array
     */
    public static function getChartAppointmentData($year = NULL){
        if(empty($year)){
            $year = formatDate(time(), 'Y');
        }

        $appointment_query      = Appointment::query()
                                             ->whereYear('created_at', $year);
        $appointment_chart_data = [];
        for($month = 1; $month <= 12; $month++){

            /** Count all appointment */
            $appointment_all_query = clone $appointment_query;
            $appointment_all_query = $appointment_all_query->whereMonth('created_at', $month)->count();

            $appointment_chart_data['all'][$month - 1] = $appointment_all_query;

            /** Count completed appointment */
            $appointment_completed_query = clone $appointment_query;
            $appointment_completed_query = $appointment_completed_query->whereMonth('created_at', $month)
                                                                       ->where('status', Appointment::COMPLETED_STATUS)
                                                                       ->count();

            $appointment_chart_data['completed'][$month - 1] = $appointment_completed_query;

            /** Count abort appointment */
            $appointment_abort_query = clone $appointment_query;
            $appointment_abort_query = $appointment_abort_query->whereMonth('created_at', $month)
                                                               ->where('status', Appointment::ABORT_STATUS)
                                                               ->count();

            $appointment_chart_data['abort'][$month - 1] = $appointment_abort_query;
        }


        return $appointment_chart_data;
    }

    /**
     * Get Data in An Year
     *
     * @param null $year
     * @return array
     */
    public static function getChartOrderData($year = NULL){
        if(empty($year)){
            $year = formatDate(time(), 'Y');
        }

        $order_query      = Order::query()
                                 ->whereYear('created_at', $year);
        $order_chart_data = [];
        for($month = 1; $month <= 12; $month++){

            /** Count all appointment */
            $order_all_query = clone $order_query;
            $order_all_query = $order_all_query->where('status', '<>', Order::STATUS_DRAFT)
                                               ->whereMonth('created_at', $month)
                                               ->sum('total_price');

            $order_chart_data['all'][$month - 1] = $order_all_query;

            /** Count completed appointment */
            $order_completed_query = clone $order_query;
            $order_completed_query = $order_completed_query->whereMonth('created_at', $month)
                                                           ->where('status', Order::STATUS_PAID)
                                                           ->sum('total_price');

            $order_chart_data['paid'][$month - 1] = $order_completed_query;

            /** Count abort appointment */
            $order_abort_query = clone $order_query;
            $order_abort_query = $order_abort_query->whereMonth('created_at', $month)
                                                   ->where('status', Appointment::ABORT_STATUS)
                                                   ->sum('total_price');

            $order_chart_data['abort'][$month - 1] = $order_abort_query;
        }


        return $order_chart_data;
    }

}