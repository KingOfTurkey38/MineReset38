# MineReset38
High performance mine reset plugin for PocketMine-MP 5.0.0
Unlike other plugins, this plugin is not threaded but asynchronous.

When a mine resets, it teleports the players inside the mine to the spawnpoint of that world.

# Reset speed
By default mines will reset at a speed of 3000 blocks a tick.
This amount can be increased/decreased in the config.

# Preview
You can learn more about the commands and how to setup in the following video https://streamable.com/e34028

# For developers
There's a cancellable event called `\kingofturkey38\minereset38\events\MineResetEvent`
If this event is cancelled, it will not reset the mine.
