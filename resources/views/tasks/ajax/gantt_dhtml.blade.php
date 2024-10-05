@php 
    echo "hello world";
@endphp
<script>
    $('#nav-tabContent').html(@json($ganttData)['data'][0].view);
    console.log(@json($ganttData)['data'][0].text_user);
</script>