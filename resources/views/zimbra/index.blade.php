<form id="frm" action="{{route("zimbra.getEmailsByDateRange")}}" method="POST">
    @csrf
    <input type="text" name="start_date" value="2025-04-16 00:00:00">
    <input type="text" name="end_date" value="2025-04-16 23:59:59">
    <button type="submit">Submit</button>
</form>
