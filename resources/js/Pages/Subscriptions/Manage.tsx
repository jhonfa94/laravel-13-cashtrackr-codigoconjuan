import SubscriptionCancellation from "@/Components/subscriptions/SubscriptionCancellation";
import SubscriptionDowngrade from "@/Components/subscriptions/SubscriptionDowngrade";
import SubscriptionResume from "@/Components/subscriptions/SubscriptionResume";
import SubscriptionStatus from "@/Components/subscriptions/SubscriptionStatus";
import SubscriptionUpgrade from "@/Components/subscriptions/SubscriptionUpgrade";
import AppLayout from "@/Layouts/AppLayout";
import { Subscription } from "@/types/subscription";


interface Props {
    subscription: Subscription;
}

const statusColors = {
    green: 'bg-green-50 text-green-600 border-green-200',
    yellow: 'bg-yellow-50 text-yellow-600 border-yellow-200',
    orange: 'bg-orange-50 text-orange-600 border-orange-200',
    red: 'bg-red-50 text-red-600 border-red-200',
    gray: 'bg-gray-50 text-gray-700 border-gray-200',
};

export default function Manage({ subscription }: Props) {


    const title = 'Administra tu suscripción';

    const isYearly = subscription.plan === 'yearly';
    const price = subscription.price;
    const status_label = subscription.status_label;



    return (
        <AppLayout title={title}>
            <main className="max-w-3xl max-auto py-12 px-4">
                <h1 className="text-3xl font-black mb-2">
                    {title}
                </h1>

                <p className="text-gray-500 mb-8">
                    Cambia tu Plan, Cancela o Reactiva tu Suscripción cuando quieras
                </p>

                <SubscriptionStatus
                    isYearly={isYearly}
                    price={price}
                    status_label={status_label}
                    color={statusColors[status_label.color]}
                />

                {subscription.on_grace_period ? (
                    <SubscriptionResume ends_at={subscription.ends_at} />
                ) : (
                    <>
                        {!isYearly && <SubscriptionUpgrade />}
                        {isYearly &&
                            <SubscriptionDowngrade
                                next_billing_date={subscription.next_billing_date}
                                ends_at={subscription.ends_at}
                            />
                            }

                            <SubscriptionCancellation
                                next_billing_date={subscription.next_billing_date}

                            />
                    </>
                )}

            </main>

        </AppLayout>
    )
}
