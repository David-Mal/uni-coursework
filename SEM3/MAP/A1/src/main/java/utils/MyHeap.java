package utils;

import value.IValue;

import java.util.*;
import exceptions.HeapException;

public class MyHeap<K, V> implements IHeap<K, V> {
    private Integer firstFreeAddress;
    private Map<K, V> heap;

    public MyHeap() {
        this.firstFreeAddress = 1;
        this.heap = new HashMap<>();
    }

    @Override
    public V get(K key) throws HeapException {
        if (!this.isDefined(key)) {
            throw new HeapException("Key now found in heap");
        }
        return this.heap.get(key);
    }

    @Override
    public void put(K key, V value) {
        this.heap.put(key, value);
    }

    @Override
    public void remove(K key) {
        this.heap.remove(key);
    }

    @Override
    public boolean isDefined(K key) {
        return this.heap.containsKey(key);
    }

    @Override
    public String toString() {
        if (this.heap.isEmpty()) {
            return "Heap is empty\n";
        }

        StringBuilder sb = new StringBuilder();
        for (K key : this.heap.keySet()) {
            sb.append(key.toString()).append(" -> ");
            sb.append(this.heap.get(key).toString()).append("\n");
        }
        return sb.toString();
    }

    @Override
    public Map<K, V> getHeap() {
        return this.heap;
    }

    @Override
    public List<V> getValues() {
        return new LinkedList<>(this.heap.values());
    }

    @Override
    public Map<Integer, IValue> safeGarbageCollector(Set<Integer>usedAddresses, Map<Integer, IValue> heap) {
        Map<Integer, IValue> newHeap = new HashMap<Integer, IValue>();
        for (Integer key : heap.keySet()) {
            if (usedAddresses.contains(key)) {
                newHeap.put(key, heap.get(key));
            }
        }
        return newHeap;
    }

    @Override
    public void setHeap(Map<K, V> heap) {
        this.heap = heap;
    }

    @Override
    public Integer allocate() {
        return this.firstFreeAddress;
    }
}
