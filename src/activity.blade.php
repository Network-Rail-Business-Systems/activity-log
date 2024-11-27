@section('main')
    @empty($activities)
        <x-govuk::p>No activities to display.</x-govuk::p>
    @else
        <x-govuk::accordion>
            @foreach($activities as $activity)
                <x-govuk::accordion-section
                    label="{{ $activity['label'] }}"
                    summary="{{ $activity['summary'] }}"
                >
                    @empty($activity['details'])
                        <x-govuk::p>No further information is available.</x-govuk::p>
                    @else
                        <x-govuk::ul spaced bulleted>
                            @foreach($activity['details'] as $detail)
                                <li>{{ $detail }}</li>
                            @endforeach
                        </x-govuk::ul>
                    @endempty
                </x-govuk::accordion-section>
            @endforeach
        </x-govuk::accordion>
    @endempty

    <x-govuk::pagination
        label="activities"
        :paginator="$pagination"
    />
@endsection
